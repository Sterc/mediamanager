<li id="{%=o.id%}" class="contentblocks-field-gallery-image">
    <input type="hidden" class="url" value="{%=o.url%}">
    <input type="hidden" class="size" value="{%=o.size%}">
    <input type="hidden" class="width" value="{%=o.width%}">
    <input type="hidden" class="height" value="{%=o.height%}">
    <input type="hidden" class="extension" value="{%=o.extension%}">
    <input type="hidden" class="file_id" value="{%=o.file_id%}">
    <div class="contentblocks-field-gallery-image-view">
        <img src="{%=o.thumbDisplay%}" alt="{%=o.title%}">
    </div>
    <input type="text" class="title" value="{%=o.title%}" placeholder="{%=_('contentblocks.title')%}">
    <textarea class="description" placeholder="{%=_('contentblocks.description')%}">{%=o.description%}</textarea>

    <div class="contentblocks-field-text contentblocks-field-link-input">
        <input type="text" id="{%=o.id%}_linkfield" class="linkfield" value="{%#o.link%}" data-link-type="{%=o.linkType%}" data-limit-to-current-context="{%=o.limit_to_current_context%}" placeholder="{%=_('contentblocks.link.placeholder')%}">
    </div>
    
    <div class="contentblocks-field-gallery-uploading">
        <div class="upload-progress">
            <div class="bar"></div>
        </div>
    </div>

    <div class="contentblocks-gallery-image-actions">
        <a href="javascript:void(0);" class="contentblocks-field-button contentblocks-gallery-image-delete">&times; {%=_('contentblocks.delete')%}</a>
    </div>
</li>
