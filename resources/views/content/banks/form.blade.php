@php $edit = isset($bank); @endphp

<form id="bankForm" method="POST" action="{{ $edit ? route('banks.update', Crypt::encryptString($bank->id)) : route('banks.store') }}">
  @csrf
  @if($edit) @method('PUT') @endif

  <div class="row">
    @php
      $fields = [
        'entity'           => 'Entity',
        // currency handled separately as select (currency_id)
        'account_name'     => 'Account Name',
        'account_number'   => 'Account Number',
        'bank_name'        => 'Bank Name',
        'branch_location'  => 'Branch Location',
        'ifsc_code'        => 'IFSC',
        'swift_code'       => 'Swift Code',
        'micr'             => 'MICR',
        'bsr_code'         => 'BSR Code',
      ];
      $required = ['entity','currency_id','account_name','account_number','bank_name'];
      $focusField = 'bank_name';
    @endphp

    {{-- Currency from master --}}
    <div class="mb-3 col-md-6">
      <label for="currency_id" class="form-label">Currency <span class="text-danger">*</span></label>
      <select id="currency_id" name="currency_id" class="form-select {{ $errors->has('currency_id') ? 'is-invalid' : '' }}" required>
        <option value="">Select Currency</option>
        @foreach($currencies as $c)
          <option value="{{ $c->id }}" {{ (int)old('currency_id', $bank->currency_id ?? 0) === (int)$c->id ? 'selected' : '' }}>
            {{ $c->name }}
          </option>
        @endforeach
      </select>
      @error('currency_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @foreach ($fields as $field => $label)
      <div class="mb-3 col-md-6">
        <label for="{{ $field }}" class="form-label">
          {{ $label }} @if(in_array($field, $required))<span class="text-danger">*</span>@endif
        </label>
        <input
          type="text"
          id="{{ $field }}"
          name="{{ $field }}"
          value="{{ old($field, $bank->$field ?? '') }}"
          placeholder="Enter {{ $label }}"
          class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}"
          @if(in_array($field, $required)) required @endif
          @if($field === $focusField) autofocus @endif
        >
        @error($field)<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    @endforeach
      <div class="mb-3 col-md-6">
    <label for="aba_number" class="form-label">ABA No.</label>
    <input type="text"
           id="aba_number"
           name="aba_number"
           inputmode="numeric"
           pattern="\d*"
           maxlength="9"
           class="form-control @error('aba_number') is-invalid @enderror"
           value="{{ old('aba_number', $bank->aba_number ?? '') }}"
           placeholder="9-digit ABA">
    @error('aba_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3 col-md-6">
    <label for="routing_number" class="form-label">Routing No.</label>
    <input type="text"
           id="routing_number"
           name="routing_number"
           inputmode="numeric"
           pattern="\d*"
           maxlength="9"
           class="form-control @error('routing_number') is-invalid @enderror"
           value="{{ old('routing_number', $bank->routing_number ?? '') }}"
           placeholder="9-digit routing">
    @error('routing_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
    {{-- Branch address (textarea) --}}
    <div class="mb-3 col-12">
      <label for="branch_address" class="form-label">Branch address</label>
      <textarea id="branch_address" name="branch_address" rows="3"
        class="form-control {{ $errors->has('branch_address') ? 'is-invalid' : '' }}"
        placeholder="Enter Branch address">{{ old('branch_address', $bank->branch_address ?? '') }}</textarea>
      @error('branch_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
     
    {{-- Status --}}
    <div class="mb-3 col-md-6">
      <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
      @php
        $statusVal = old('status', isset($bank) ? $bank->status : 1);
      @endphp
      <select name="status" id="status" class="form-control form-select @error('status') is-invalid @enderror" required>
        <option value="">Select Status</option>
        <option value="1" {{ (string)$statusVal === '1' ? 'selected' : '' }}>Active</option>
        <option value="0" {{ (string)$statusVal === '0' ? 'selected' : '' }}>Inactive</option>
      </select>
      @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

  </div>

  <div class="text-end">
    <a href="{{ route('banks.index') }}" class="btn btn-info">
      <i class="ti ti-chevron-left me-sm-1 me-0 mb-1"></i> Back
    </a>
    <button type="submit" class="btn btn-primary">
      {{ $edit ? 'Update' : 'Save' }} <i class="ti ti-file-upload ms-1 mb-1"></i>
    </button>
  </div>
</form>