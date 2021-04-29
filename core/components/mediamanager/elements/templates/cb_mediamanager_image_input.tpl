<div class="contentblocks-field contentblocks-field-mm-image gallery-size-medium">
    <input type="hidden" class="url" value="{%=o.url%}">
    <input type="hidden" class="width" value="{%=o.width%}">
    <input type="hidden" class="height" value="{%=o.height%}">
    <input type="hidden" class="file_id" value="{%=o.file_id%}">

    <div class="contentblocks-field-actions">
        <button type="button" class="contentblocks-field-delete-image contentblocks-field-button">&times; {%=_('contentblocks.delete_image')%}</button>
    </div>

    <label>{%=o.name%}</label>
    <div class="contentblocks-field-file-upload">
        <a href="javascript:void(0);" class="big contentblocks-field-button contentblocks-field-file-choose">{%=_('contentblocks.choose')%}</a>
    </div>

    <div class="contentblocks-field-image-preview">
        <ul class="gallery-image-holder">
            <li class="contentblocks-field-gallery-image">
                <div class="contentblocks-field-gallery-image-view">
                    <img src="{%=o.thumbDisplay%}" alt="{%=o.title%}">
                </div>

                <input type="text" class="contentblocks-field-image-title-input" value="{%=o.title%}" placeholder="Title">
            </li>
        </ul>

    </div>
</div>
