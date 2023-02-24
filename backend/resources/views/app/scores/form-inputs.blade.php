@php $editing = isset($score) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="value"
            label="Value"
            :value="old('value', ($editing ? $score->value : ''))"
            maxlength="255"
            placeholder="Value"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="mode"
            label="Mode"
            :value="old('mode', ($editing ? $score->mode : ''))"
            maxlength="255"
            placeholder="Mode"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="player_id" label="Player" required>
            @php $selected = old('player_id', ($editing ? $score->player_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Player</option>
            @foreach($players as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
