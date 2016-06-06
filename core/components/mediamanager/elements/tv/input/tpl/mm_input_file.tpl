<div id="tv-image-{$tv->id}"></div>
<div class="x-form-field-wrap x-form-field-trigger-wrap mediamanager-input-wrapper" data-target="#modal-wrapper-{$tv->id}" data-trigger="#mediamanager-input-{$tv->id}" data-tvid="{$tv->id}">
    <input name="tv{$tv->id}" id="tv{$tv->id}" type="text" value="{$tv->value}" class="textfield x-form-text x-form-field" />
    <div class="x-form-trigger x-form-image-trigger mediamanager-input" id="mediamanager-input-{$tv->id}" data-selected-value="#selected-value-{$tv->id}"></div>
</div>

<div class="selected-value" id="selected-value-{$tv->id}"></div>