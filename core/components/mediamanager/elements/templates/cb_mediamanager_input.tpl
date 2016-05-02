<div class="contentblocks-field contentblocks-field-text">
    <div class="contentblocks-field-actions">
        <a href="javascript:void(0);" class="contentblocks-field-delete-image">× Delete Image</a>
    </div>
    <label for="{%=o.generated_id%}_textfield">{%=o.name%}</label>
    <div class="contentblocks-field-text contentblocks-field-text-input">
        <input type="hidden" id="{%=o.generated_id%}_textfield" value="{%=o.value%}">
    </div>
</div>

<div class="contentblocks-field contentblocks-field-mm-image">
    <input type="hidden" class="url" />
    <input type="hidden" class="size" />
    <input type="hidden" class="width" />
    <input type="hidden" class="height" />
    <input type="hidden" class="extension" />
    <div class="contentblocks-field-actions">
        <a href="javascript:void(0);" class="contentblocks-field-delete-image">&times; {%=_('contentblocks.delete_image')%}</a>
    </div>

    <label>{%=o.name%}</label>
    <div class="contentblocks-field-image-upload">
        <a href="javascript:void(0);" class="big contentblocks-field-button contentblocks-field-image-choose">{%=_('contentblocks.choose')%}</a>
        <a href="javascript:void(0);" class="big contentblocks-field-button contentblocks-field-upload">{%=_('contentblocks.upload')%}</a>
        {%=_('contentblocks.image.or_drop_image')%}
        <input type="file" id="{%=o.generated_id%}-upload" class="contentblocks-field-upload-field">
    </div>
    <div class="contentblocks-field-image-uploading">
        <div class="upload-progress">
            <div class="bar"></div>
        </div>
    </div>
    <div class="contentblocks-field-image-preview">
        <img />
    </div>
</div>
