<div class="form-section">
    <h3 class="section-title">[[%mediamanager.files.licensing.title]]</h2>
    <div class="form-group">
        <label for="image_createdon">[[%mediamanager.files.image_creation_date]]</label>
        <input type="date" name="l[image_createdon]" id="image_createdon" value="[[+date_today]]" class="form-control" data-file-license/>
    </div>

    <div class="form-group">
        <label for="image_source">[[%mediamanager.files.image_source]]</label>
        <select name="l[image_source]" id="image_source" class="form-control" data-file-license>
            <option value="">[[%mediamanager.files.image_source_select]]</option>
            [[+source_options]]
        </select>

        <small>[[%mediamanager.files.image_source_help]]</small>
    </div>

    <h3 class="section-title">[[%mediamanager.files.image_validity.title]]</h3>
    <div class="form-group">
        <label for="image_valid_startdate">[[%mediamanager.files.image_valid_startdate]]</label>
        <input type="date" name="l[image_valid_startdate]" id="image_valid_startdate" value="[[+date_today]]" class="form-control" data-file-license/>
    </div>

    <div class="form-group">
        <label for="image_valid_enddate">[[%mediamanager.files.image_valid_enddate]]</label>
        <input type="date" name="l[image_valid_enddate]" id="image_valid_enddate" value="[[+date_today]]" class="form-control" data-file-license/>
    </div>

    <div class="form-group">
        <label>[[%mediamanager.files.license_exists]]</label>
        <div class="radio">
            <label>
                <input type="radio" name="l[license_exists]" value="0" data-file-license>
                [[%mediamanager.global.no]]
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="l[license_exists]" value="1" data-file-license>
                [[%mediamanager.global.yes]]
            </label>
        </div>
    </div>

    <div class="form-group">
        <label>[[%mediamanager.files.license_depicted_consent]]</label>
        <div class="radio">
            <label>
                <input type="radio" name="l[license_depicted_consent]" value="0" data-file-license>
                [[%mediamanager.global.no]]
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="l[license_depicted_consent]" value="1" data-file-license>
                [[%mediamanager.global.yes]]
            </label>
        </div>
    </div>

    <div class="form-group">
        <label>[[%mediamanager.files.license_file]]</label>
        <input type="file" name="l[license_file]" class="form-control" data-file-license/>
        <small>[[%mediamanager.files.license_file_help? &extensions=`[[+license_file_extensions]]`]]</small>
    </div>
</div>