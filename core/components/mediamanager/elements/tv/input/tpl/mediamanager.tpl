<div id="tv-image-{$tv->id}"></div>
<div id="tv-image-preview-{$tv->id}" class="modx-tv-image-preview">
    {if $tv->value}<img src="{$_config.connectors_url}system/phpthumb.php?w=400&h=400&aoe=0&far=0&src={$tv->value}&source={$source}" alt="" />{/if}
</div>

<a id="selectFile" href="http://mediamanager.nl.joeke/manager/?a=home&namespace=mediamanager">Select file</a>

<div id="dialogWrapper">
    <div id="dialog"></div>
    <iframe id="mediamanager"></iframe>
</div>