@extends('layouts/layoutMaster')

@section('title', 'Collaboration')

@section('page-style')
<style>
  .chat-message .sender {
    font-size: 0.8rem;
    color: #444;
    display: block;
    margin-bottom: 2px;
  }
  .chat-message.own .sender {
    color: #eee;
  }

  .chat-window {
    height: 300px;
    overflow-y: auto;
    border: 1px solid #e0e0e0;
    border-radius: .5rem;
    padding: 1rem;
    background: #fafafa;
  }
  .chat-message { margin-bottom: 1rem; }
  .chat-message .meta { font-size: .75rem; color: #666; }
  .chat-message.own { text-align: right; }
  .chat-message.own .bubble { background: #007bff; color: #fff; }
  .chat-message .bubble {
    display: inline-block; padding: .5rem .75rem; border-radius: .5rem;
    background: #f1f1f1; max-width: 70%; word-wrap: break-word;
  }
</style>
@endsection

@section('content')

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Project: {{ $project->project_name }}</h5>
    <a href="{{ route('collab.inbox') }}" class="btn btn-outline-secondary">
      <i class="ti ti-chevron-left me-1"></i> Back
    </a>
  </div>

  <div class="card-body d-flex flex-column">
    {{-- Messages list --}}
    <div id="chatWindow" class="chat-window mb-3" data-conversation="{{ $conversation->id }}"></div>

    {{-- Message input --}}
    <form id="chatForm" class="d-flex" autocomplete="off">
      <input type="text" id="chatInput" class="form-control me-2" placeholder="Type a message…" required>
      <button type="submit" class="btn btn-primary">
        <i class="ti ti-send"></i>
      </button>
    </form>
  </div>
</div>
@endsection

@section('extra-script')
{{-- Pusher client + Echo IIFE (UMD) --}}
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>

<script>
(function () {
  // ------------------- Context -------------------
  const chatWindow     = document.getElementById('chatWindow');
  const form           = document.getElementById('chatForm');
  const input          = document.getElementById('chatInput');
  const conversationId = chatWindow?.dataset?.conversation;

  const csrf    = '{{ csrf_token() }}';
  const userId  = {{ auth()->id() }};
  const userTyp = @json(get_class(auth()->user()));

  if (!conversationId) {
    console.error('Missing conversationId on #chatWindow');
    return;
  }

  const routes = {
    list:  "{{ url('/conversations') }}/" + conversationId + "/messages",
    send:  "{{ url('/conversations') }}/" + conversationId + "/messages",
    read:  "{{ url('/conversations') }}/" + conversationId + "/read",
  };

  // ------------------- Helpers -------------------
  function escapeHtml(s) {
    return String(s || '').replace(/[&<>"']/g, m => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
    }[m]));
  }
  function renderMessage(msg) {
  const own = String(msg.sender_id) === String({{ auth()->id() }}) &&
              msg.sender_type === @json(get_class(auth()->user()));

  const div = document.createElement('div');
  div.className = 'chat-message' + (own ? ' own' : '');

  div.innerHTML = `
    <div class="bubble">
      <strong class="sender">${own ? 'You' : (msg.sender_name || 'Unknown')}</strong><br>
      ${String(msg.body || '').replace(/[&<>"']/g, m => ({
        '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'
      }[m]))}
    </div>
    <div class="meta">${new Date(msg.sent_at || msg.created_at).toLocaleString()}</div>
  `;

  chatWindow.appendChild(div);
  chatWindow.scrollTop = chatWindow.scrollHeight;
}


  async function loadMessages() {
    try {
      const res = await fetch(routes.list + '?limit=50', { headers: { 'Accept':'application/json' } });
      const data = await res.json();
      chatWindow.innerHTML = '';
      (data.data || []).reverse().forEach(renderMessage);

      // mark last as read
      if (data.data && data.data.length > 0) {
        const lastId = data.data[0].id;
        fetch(routes.read, {
          method: 'POST',
          headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
          credentials: 'same-origin',
          body: JSON.stringify({ message_id: lastId })
        }).catch(()=>{});
      }
    } catch (e) { console.error('Failed to load messages', e); }
  }

  // ------------------- Send (broadcast-only render) -------------------
  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    const text = input.value.trim();
    if (!text) return;
    try {
      const res = await fetch(routes.send, {
        method: 'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
        credentials: 'same-origin',
        body: JSON.stringify({ body: text })
      });
      if (!res.ok) throw new Error('Send failed: ' + res.status);
      input.value = '';
      // Do NOT render here. We render only on broadcast to prevent duplicates.
    } catch (e) { console.error(e); }
  });

  // ------------------- Echo init (IIFE-safe) -------------------
  // Choose correct constructor: some IIFE builds expose LaravelEcho, some Echo
  window.Pusher = window.Pusher || Pusher;
  const EchoCtor = (typeof window.LaravelEcho === 'function')
    ? window.LaravelEcho
    : (typeof window.Echo === 'function' ? window.Echo : null);

  if (!EchoCtor) {
    console.error('Laravel Echo IIFE not loaded. typeof window.Echo =', typeof window.Echo, '; typeof window.LaravelEcho =', typeof window.LaravelEcho);
    return;
  }

  // Create ONE Echo instance globally (window.echo)
  if (!window.echo) {
    // Pusher.logToConsole = true; // uncomment for verbose debugging
    window.echo = new EchoCtor({
      broadcaster: 'pusher',
      key: '{{ config('broadcasting.connections.pusher.key') }}',
      cluster: '{{ config('broadcasting.connections.pusher.options.cluster') ?? "mt1" }}',
      wsHost: '{{ config('broadcasting.connections.pusher.options.host')
  ?? ('ws-' . (config('broadcasting.connections.pusher.options.cluster') ?? 'mt1') . '.pusher.com') }}',

      wsPort: {{ config('broadcasting.connections.pusher.options.port', 443) }},
      wssPort: {{ config('broadcasting.connections.pusher.options.port', 443) }},
      forceTLS: true,
      enabledTransports: ['ws','wss'],

      // Custom authorizer: POST with session cookie + CSRF, avoid preflight
      authorizer: (channel) => ({
        authorize: (socketId, callback) => {
          fetch('/broadcasting/auth', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
              'Accept': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': csrf,
            },
            body: new URLSearchParams({ socket_id: socketId, channel_name: channel.name })
          })
          .then(r => r.ok ? r.json() : Promise.reject(r))
          .then(data => callback(null, data))
          .catch(err => callback(err, null));
        }
      })
    });
  }

  // ------------------- Subscribe once; dedupe renders -------------------
  window._chat = window._chat || {};
  const chanKey = 'conversation.' + conversationId;

  // If switching conversations, stop old listener; else clean same-channel listener
  if (!window._chat.channel || window._chat.chanKey !== chanKey) {
    if (window._chat.channel && typeof window._chat.channel.stopListening === 'function') {
      window._chat.channel.stopListening('.MessageSent');
    }
    window._chat.channel = window.echo.private(chanKey);
    window._chat.chanKey = chanKey;
    window._chat.seen   = new Set(); // reset de-dup for this conversation
  } else {
    if (typeof window._chat.channel.stopListening === 'function') {
      window._chat.channel.stopListening('.MessageSent');
    }
  }

  function renderOnce(payload) {
    if (!payload || !payload.id) return;
    if (window._chat.seen.has(payload.id)) return;
    window._chat.seen.add(payload.id);
    renderMessage(payload);
  }

  window._chat.channel.listen('.MessageSent', renderOnce);

  // ------------------- Initial data -------------------
  loadMessages();
})();
</script>
@endsection
