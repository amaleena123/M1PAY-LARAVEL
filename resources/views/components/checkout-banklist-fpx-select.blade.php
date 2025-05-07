<select name="bank" id="bank_list" class="form-select">
     <option value="">Select Bank (Online Banking Only)</option>
    @foreach ($banks as $bank)
        <option value="{{ $bank['bankId'] }}">{{ $bank['title'] }} ({{ $bank['fpxOnline'] == 1 ? "Online" : "Offline" }})</option>
    @endforeach
</select>
