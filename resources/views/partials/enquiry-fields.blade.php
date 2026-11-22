{{-- Shared enquiry fields (modal + contact page). $prefix keeps element ids unique per form. --}}
@php
    $p = $prefix ?? 'eq';
    $countries = $navDestinations->pluck('name');
    $old = fn ($key, $default = null) => old($key, $default);
@endphp
<div class="hp-field" aria-hidden="true">
    <label for="{{ $p }}-website">Website</label>
    <input type="text" id="{{ $p }}-website" name="website" tabindex="-1" autocomplete="off">
</div>
<div class="row g-3">
    <div class="col-12" data-field>
        <label class="form-label" for="{{ $p }}-name">Full name <span class="req">*</span></label>
        <input class="form-control @error('full_name') is-invalid @enderror" id="{{ $p }}-name" name="full_name" value="{{ $old('full_name') }}" placeholder="e.g. Aryan Sharma" autocomplete="name" required maxlength="120">
        @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6" data-field>
        <label class="form-label" for="{{ $p }}-phone">Phone / WhatsApp <span class="req">*</span></label>
        <input class="form-control @error('phone') is-invalid @enderror" id="{{ $p }}-phone" name="phone" type="tel" value="{{ $old('phone') }}" placeholder="+91 98765 43210" autocomplete="tel" required maxlength="30">
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6" data-field>
        <label class="form-label" for="{{ $p }}-email">Email address <span class="req">*</span></label>
        <input class="form-control @error('email') is-invalid @enderror" id="{{ $p }}-email" name="email" type="email" value="{{ $old('email') }}" placeholder="you@example.com" autocomplete="email" required maxlength="190">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6" data-field>
        <label class="form-label" for="{{ $p }}-qual">Highest qualification</label>
        <select class="form-select @error('qualification') is-invalid @enderror" id="{{ $p }}-qual" name="qualification">
            @foreach (\App\Models\Enquiry::QUALIFICATIONS as $q)
                <option @selected($old('qualification', "Bachelor's Degree") === $q)>{{ $q }}</option>
            @endforeach
        </select>
        @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6" data-field>
        <label class="form-label" for="{{ $p }}-gpa">Percentage / GPA <span class="req">*</span></label>
        <input class="form-control @error('gpa') is-invalid @enderror" id="{{ $p }}-gpa" name="gpa" value="{{ $old('gpa') }}" placeholder="e.g. 8.2 CGPA or 78%" required maxlength="40">
        @error('gpa')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6" data-field>
        <label class="form-label" for="{{ $p }}-course">Preferred course area</label>
        <select class="form-select @error('preferred_course') is-invalid @enderror" id="{{ $p }}-course" name="preferred_course">
            @foreach (\App\Models\Enquiry::COURSES as $c)
                <option @selected($old('preferred_course') === $c)>{{ $c }}</option>
            @endforeach
        </select>
        @error('preferred_course')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6" data-field>
        <label class="form-label" for="{{ $p }}-country">Preferred country</label>
        <select class="form-select @error('preferred_country') is-invalid @enderror" id="{{ $p }}-country" name="preferred_country">
            @foreach ($countries as $c)
                <option value="{{ $c }}" @selected($old('preferred_country', $defaultCountry ?? 'Germany') === $c)>{{ $c }}</option>
            @endforeach
        </select>
        @error('preferred_country')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-sm-6" data-field>
        <label class="form-label" for="{{ $p }}-intake">Target intake</label>
        <select class="form-select @error('preferred_intake') is-invalid @enderror" id="{{ $p }}-intake" name="preferred_intake">
            @foreach (\App\Models\Enquiry::INTAKES as $i)
                <option @selected($old('preferred_intake') === $i)>{{ $i }}</option>
            @endforeach
        </select>
        @error('preferred_intake')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    @if ($withMessage ?? false)
        <div class="col-12" data-field>
            <label class="form-label" for="{{ $p }}-message">Anything else we should know? <span class="text-muted-ef fw-normal">(optional)</span></label>
            <textarea class="form-control @error('message') is-invalid @enderror" id="{{ $p }}-message" name="message" rows="3" maxlength="2000" placeholder="Target universities, budget, test scores, questions…">{{ $old('message') }}</textarea>
            @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    @endif
</div>
