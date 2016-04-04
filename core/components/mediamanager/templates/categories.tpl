<div id="mediamanager-categories" class="mediamanager-categories">
    <div class="row">
        <div class="col-xs-12">
            <h1>{$pagetitle} {$mediamanager.categories.category}</h1>
        </div>
        <div class="col-xs-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">{$create_title}</h4>
                </div>
                <div class="panel-body">
                    <div data-create-feedback></div>
                    <form action="{$connector_url}" method="post" data-create-form>
                        <input type="hidden" class="form-control" name="action" value="mgr/categories">
                        <input type="hidden" class="form-control" name="method" value="create">
                        <input type="hidden" name="HTTP_MODAUTH" value="{$token}">

                        <div class="form-group">
                            <label>{$create_parent_label}</label>
                            <select class="form-control" name="parent">
                                {$parent_options}
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{$create_label}</label>
                            <input type="text" class="form-control" name="name" placeholder="{$create_placeholder}">
                        </div>

                        <button type="submit" class="btn btn-success">{$create_button}</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-9" data-fit-to>
            <div class="panel panel-default" data-panel-fit>
                <div class="panel-body" data-listing>
                    {$list}
                </div>
            </div>
        </div>
    </div>
</div>