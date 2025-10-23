<!-- Asset -->
<div id="{{ $asset_selector_div_id ?? "assigned_asset" }}"
     class="form-group{{ $errors->has($fieldname) ? ' has-error' : '' }}"{!!  (isset($style)) ? ' style="'.e($style).'"' : ''  !!}>
    <label for="{{ $fieldname }}" class="col-md-3 control-label">{{ $translated_name }}</label>
    <div class="col-md-7">
        <select class="js-data-ajax select2"
                data-endpoint="hardware"
                data-placeholder="{{ trans('general.select_asset') }}"
                aria-label="{{ $fieldname }}"
                name="{{ $fieldname }}"
                style="width: 100%"
                id="{{ (isset($select_id)) ? $select_id : 'assigned_asset_select' }}"
                {{ ((isset($multiple)) && ($multiple === true)) ? ' multiple' : '' }}
                {!! (!empty($asset_status_type)) ? ' data-asset-status-type="' . $asset_status_type . '"' : '' !!}
                {!! (!empty($company_id)) ? ' data-company-id="' .$company_id.'"'  : '' !!}
                {{  ((isset($required) && ($required =='true'))) ?  ' required' : '' }}
        >

            @if ((!isset($unselect)) && ($asset_id = old($fieldname, (isset($asset) ? $asset->id  : (isset($item) ? $item->{$fieldname} : '')))))
                <option value="{{ $asset_id }}" selected="selected" role="option" aria-selected="true"  role="option">
                    {{ (\App\Models\Asset::find($asset_id)) ? \App\Models\Asset::find($asset_id)->present()->fullName : '' }}
                </option>
            @else
                @if(!isset($multiple))
                    <option value=""  role="option">{{ trans('general.select_asset') }}</option>
                @else
                    @if(isset($asset_ids))
                        @foreach($asset_ids as $asset_id)
                            <option value="{{ $asset_id }}" selected="selected" role="option" aria-selected="true"
                                    role="option">
                                {{ (\App\Models\Asset::find($asset_id)) ? \App\Models\Asset::find($asset_id)->present()->fullName : '' }}
                            </option>
                        @endforeach
                    @endif
                @endif
            @endif
        </select>
    </div>
    {!! $errors->first($fieldname, '<div class="col-md-8 col-md-offset-3"><span class="alert-msg" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i> :message</span></div>') !!}

</div>

<div id="{{ $asset_selector_div_id ?? "assigned_asset" }}_error" class="error-message text-danger" style="display: none;"><i class="fas fa-exclamation-triangle"></i> {{ trans('general.asset_already_added') }}</div>

<script nonce="{{ csrf_token() }}">
    $(document).ready(function() {
        var selectId = "{{ (isset($select_id)) ? $select_id : 'assigned_asset_select' }}";

        // Handler für die Barcode-Eingabe
        $('#' + selectId).on('keypress', function(e) {
            if (e.which === 13) {
                e.preventDefault();
                var inputVal = $(this).val();
                var errorDiv = $('#{{ $asset_selector_div_id ?? "assigned_asset" }}_error');

                // Überprüfen, ob das Asset bereits ausgewählt wurde
                var isAlreadySelected = false;

                // Für Einzelauswahl
                if (!$(this).prop('multiple')) {
                    isAlreadySelected = $(this).val() === inputVal;
                }
                // Für Mehrfachauswahl
                else {
                    var selectedValues = $(this).val() || [];
                    isAlreadySelected = selectedValues.includes(inputVal);
                }

                if (isAlreadySelected) {
                    errorDiv.text('{{ trans('general.asset_already_added') }}').show();
                    setTimeout(function() {
                        errorDiv.hide();
                    }, 3000); // Fehlermeldung nach 3 Sekunden ausblenden
                } else {
                    errorDiv.hide();
                }

                // Focus zurück auf das Eingabefeld setzen
                $(this).select2('open');
            }
        });

        // Fehler ausblenden bei anderen Events
        $('#' + selectId).on('change', function() {
            $('#{{ $asset_selector_div_id ?? "assigned_asset" }}_error').hide();
        });
    });
</script>