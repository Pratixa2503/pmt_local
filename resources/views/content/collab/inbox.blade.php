@extends('layouts/layoutMaster')

@section('title', 'Collaboration • Inbox')

@section('page-style')
<style>
  .proj-grid { display:grid; grid-template-columns: repeat(auto-fill,minmax(320px,1fr)); gap:16px; }
  .proj-card { border:1px solid #eaeaea; border-radius:12px; padding:14px; background:#fff; transition:.15s transform, .15s box-shadow; }
  .proj-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.06); }
  .proj-title { font-weight:600; color:#222; display:flex; align-items:center; gap:8px; }
  .badge-unread { background:#e74c3c; color:#fff; border-radius:999px; padding:2px 8px; font-size:.75rem; }
  .preview { color:#555; margin-top:6px; min-height: 38px; }
  .meta { font-size:.8rem; color:#888; display:flex; justify-content:space-between; margin-top:8px; }
  .enter-btn { display:inline-flex; align-items:center; gap:6px; margin-top:10px; }
  .empty { text-align:center; padding:48px 0; color:#777; }
</style>
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Collaboration</h5>
  </div>

  <div class="card-body">
    @if($rows->isEmpty())
      <div class="empty">No projects available for your role.</div>
    @else
      <div class="proj-grid" id="projGrid">
        @foreach($rows as $r)
          @php
            $preview = $r->last_message_preview ?: 'No messages yet';
            $dt = $r->last_message_at ? \Carbon\Carbon::parse($r->last_message_at)->diffForHumans() : '—';
          @endphp
          <div class="proj-card conv-card"
               data-conversation="{{ $r->conversation_id }}"
               data-url="{{ route('conversations.show', $r->conversation_id) }}">
            <div class="proj-title">
              <span class="ti ti-messages"></span>
              <span>{{ $r->project_name }}</span>
              <span class="ms-auto badge-unread unread" {{ $r->unread_count ? '' : 'style=display:none;' }}>
                <span class="unread-num">{{ $r->unread_count }}</span>
              </span>
            </div>
            <div class="preview">
              <span class="preview-text">{{ \Illuminate\Support\Str::limit($preview, 120) }}</span>
            </div>
            <div class="meta">
              <span class="last-time">{{ $dt }}</span>
              <a class="enter-btn btn btn-sm btn-primary" href="{{ route('conversations.show', $r->conversation_id) }}">
                Open <i class="ti ti-chevron-right"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
</div>
@endsection

@section('extra-script')
{{-- Pusher + Echo (CDN/IIFE) --}}
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>
<script>
(function () {
  const grid = document.getElementById('projGrid');
  if (!grid) return;

  const csrf = '{{ csrf_token() }}';
  const meId = {{ auth()->id() }};
  const meTp = @json(get_class(auth()->user()));

  // Cards map: convId -> DOM
  const cards = {};
  document.querySelectorAll('.conv-card').forEach(card => {
    const cid = card.dataset.conversation;
    cards[cid] = {
      el: card,
      unreadWrap: card.querySelector('.unread'),
      unreadNum:  card.querySelector('.unread-num'),
      previewEl:  card.querySelector('.preview-text'),
      timeEl:     card.querySelector('.last-time'),
    };
  });

  // Echo init (IIFE-safe)
  window.Pusher = window.Pusher || Pusher;
  const EchoCtor = (typeof window.LaravelEcho === 'function') ? window.LaravelEcho
                   : (typeof window.Echo === 'function' ? window.Echo : null);
  if (!EchoCtor) { console.error('Echo IIFE not loaded'); return; }

  if (!window.echo) {
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
      authorizer: (channel) => ({
        authorize: (socketId, callback) => {
          fetch('/broadcasting/auth', {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
            body: new URLSearchParams({ socket_id: socketId, channel_name: channel.name })
          })
          .then(r => r.ok ? r.json() : Promise.reject(r))
          .then(data => callback(null, data))
          .catch(err => callback(err, null));
        }
      })
    });
  }

  // Subscribe to each conversation; live-update unread & preview
  Object.keys(cards).forEach(cid => {
    const chan = window.echo.private('conversation.' + cid);
    chan.stopListening('.MessageSent'); // avoid duplicates on partial reload
    chan.listen('.MessageSent', (e) => {
      // Ignore my own messages for unread bumps
      if (String(e.sender_id) === String(meId) && e.sender_type === meTp) {
        // still update preview/time so the card feels live
      } else {
        const c = cards[cid];
        if (!c) return;
        const current = parseInt(c.unreadNum?.textContent || '0', 10) || 0;
        if (c.unreadWrap) c.unreadWrap.style.display = '';
        if (c.unreadNum)  c.unreadNum.textContent = String(current + 1);
      }

      // Update preview + time on any new message
      const c2 = cards[cid];
      if (c2?.previewEl) c2.previewEl.textContent = (e.body || '').substring(0, 120);
      if (c2?.timeEl)    c2.timeEl.textContent    = 'just now';
    });
  });
})();

// On conversation page load
const convId = document.getElementById('chatWindow').dataset.conversation;

// Tell server "mark read"
fetch(`/conversations/${convId}/read-all`, {
  method: 'POST',
  headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
});

</script>
@endsection
