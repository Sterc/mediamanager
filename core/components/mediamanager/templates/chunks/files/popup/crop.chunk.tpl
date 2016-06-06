<div data-crop-message></div>

<div class="row">

    <div class="col-md-8">

        <img src="[[+file.path]]" class="crop" />

    </div>

    <div class="col-md-4">

        <div class="crop-preview clearfix">
            <div class="img-preview"></div>
        </div>

        <div class="crop-data">
            <div class="input-group input-group-sm">
                <label class="input-group-addon">X</label>
                <input type="text" class="form-control" data-crop-x placeholder="x">
                <span class="input-group-addon">px</span>
            </div>
            <div class="input-group input-group-sm">
                <label class="input-group-addon">Y</label>
                <input type="text" class="form-control" data-crop-y placeholder="y">
                <span class="input-group-addon">px</span>
            </div>
            <div class="input-group input-group-sm">
                <label class="input-group-addon">Width</label>
                <input type="text" class="form-control" data-crop-width placeholder="width">
                <span class="input-group-addon">px</span>
            </div>
            <div class="input-group input-group-sm">
                <label class="input-group-addon">Height</label>
                <input type="text" class="form-control" data-crop-height placeholder="height">
                <span class="input-group-addon">px</span>
            </div>
            <div class="input-group input-group-sm">
                <label class="input-group-addon">Rotate</label>
                <input type="text" class="form-control" data-crop-rotate placeholder="rotate">
                <span class="input-group-addon">deg</span>
            </div>
            <div class="input-group input-group-sm">
                <label class="input-group-addon">ScaleX</label>
                <input type="text" class="form-control" readonly data-crop-scale-x placeholder="scaleX">
            </div>
            <div class="input-group input-group-sm">
                <label class="input-group-addon">ScaleY</label>
                <input type="text" class="form-control" readonly data-crop-scale-y placeholder="scaleY">
            </div>
        </div>

    </div>

</div>

<div class="row">

    <div class="col-md-12 crop-buttons">

        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
            <span data-toggle="tooltip" title="Zoom In">
              <span class="fa fa-search-plus"></span>
            </span>
            </button>
            <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
            <span data-toggle="tooltip" title="Zoom Out">
              <span class="fa fa-search-minus"></span>
            </span>
            </button>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
            <span data-toggle="tooltip" title="Move Left">
              <span class="fa fa-arrow-left"></span>
            </span>
            </button>
            <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
            <span data-toggle="tooltip" title="Move Right">
              <span class="fa fa-arrow-right"></span>
            </span>
            </button>
            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
            <span data-toggle="tooltip" title="Move Up">
              <span class="fa fa-arrow-up"></span>
            </span>
            </button>
            <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
            <span data-toggle="tooltip" title="Move Down">
              <span class="fa fa-arrow-down"></span>
            </span>
            </button>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
            <span data-toggle="tooltip" title="Rotate Left">
              <span class="fa fa-rotate-left"></span>
            </span>
            </button>
            <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
            <span data-toggle="tooltip" title="Rotate Right">
              <span class="fa fa-rotate-right"></span>
            </span>
            </button>
        </div>

        <div class="btn-group">
            <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
            <span data-toggle="tooltip" title="Reset">
              <span class="fa fa-refresh"></span>
            </span>
            </button>
        </div>

    </div>

</div>