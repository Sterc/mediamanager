<div id="tv-image-{$tv->id}"></div>
<div id="tv-image-preview-{$tv->id}" class="modx-tv-image-preview">
    {if $tv->value}
        {if $tv->value|substr:-3 == "svg"}
        <img src="/{$params.basePath}{$tv->value}" alt="" width="150"/>
        {else}
        <img src="{$_config.connectors_url}system/phpthumb.php?w=400&src={$tv->value}&source={$source}" alt="" />
        {/if}
    {/if}
</div>
<div class="x-form-field-wrap x-form-field-trigger-wrap mediamanager-input-wrapper" data-target="#modal-wrapper-{$tv->id}" data-trigger="#mediamanager-input-{$tv->id}">
    <input name="tv{$tv->id}" id="tv{$tv->id}" type="text" value="{$tv->value}" class="textfield x-form-text x-form-field" />
    <div class="x-form-trigger x-form-image-trigger mediamanager-input" id="mediamanager-input-{$tv->id}" data-selected-value="#selected-value-{$tv->id}"></div>
</div>

<div class="selected-value" id="selected-value-{$tv->id}"></div>