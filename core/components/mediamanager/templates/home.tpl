<div id="mediamanager-browser" class="mediamanager-browser">

    <div class="row">
        <div class="col-sm-12">
            <h1>
                <span>Media Manager</span>
                <button type="button" class="btn btn-success upload-media">Upload Media</button>
                <button type="button" class="btn btn-default new-category">New Category</button>
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <form action="/mediamanager/assets/components/mediamanager/connector.php" class="dropzone" id="dropzone">
                <h2>Drop files here to upload</h2>
                <button type="button" class="btn btn-default btn-lg">Or select files</button>
                <div class="fallback">
                    <input name="file" type="file" multiple />
                </div>
                <p class="max-upload-size">Maximum upload file size: 10 MB.</p>

                <div class="dropzone-previews"></div>

                <div class="dropzone-actions">
                    <button type="button" class="btn btn-success pull-right upload-selected-files">Upload Selected Files</button>
                </div>
            </form>

        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-2">

            <select class="form-control select-context">
                <option>Media Context</option>
            </select>

            <div class="categories-tree"></div>

        </div>

        <div class="col-sm-9 col-md-10">

            <div class="media-header">

                <div class="form-inline">

                    <div class="search-form pull-right">
                        <select class="form-control">
                            <option>Sorting</option>
                        </select>
                        <input type="input" class="form-control" placeholder="Search">
                        <button type="button" class="btn btn-default advanced-search">Advanced Search</button>
                    </div>

                    <div class="clearfix"></div>

                    <div class="panel panel-default advanced-search-filters">
                        <div class="panel-body">

                            <select class="form-control">
                                <option>Media type</option>
                            </select>

                            <select class="form-control">
                                <option>Categories</option>
                            </select>

                            <select class="form-control">
                                <option>Tags</option>
                            </select>

                            <select class="form-control">
                                <option>Uploaded by</option>
                            </select>

                            <select class="form-control">
                                <option>Date</option>
                            </select>

                        </div>
                    </div>

                </div>

            </div>

            <div class="media-container">

                <div class="panel panel-default">
                    <div class="panel-body">

                        files

                    </div>
                </div>

            </div>

            <div class="media-footer">

            </div>

        </div>
    </div>

</div>