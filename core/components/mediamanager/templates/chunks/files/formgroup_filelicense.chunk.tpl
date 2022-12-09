<div class="form-section">
    <h3 class="section-title">[[%mediamanager.files.licensing.title]]</h3>
    <div class="form-group">
        <label for="image_createdon">[[%mediamanager.files.image_creation_date]]</label>
        <input type="date" name="license[image_createdon]" id="image_createdon" class="form-control" value="[[+licensing.image_createdon:date=`%Y-%m-%d`]]"/>
    </div>

    <div class="form-group">
        <label for="image_source">[[%mediamanager.files.image_source]] *</label>
        <select name="license[image_source]" id="image_source" class="form-control">
            <option value="">[[%mediamanager.files.image_source_select]]</option>
            [[+licensing.source_options]]
        </select>
        <small>[[%mediamanager.files.image_source_help]]</small>
    </div>

    <h3 class="section-title">[[%mediamanager.files.image_validity.title]]</h3>
    <div class="form-group">
        <label for="image_valid_startdate">[[%mediamanager.files.image_valid_startdate]] *</label>
        <input type="date" name="license[image_valid_startdate]" id="image_valid_startdate" value="[[+licensing.image_valid_startdate:date=`%Y-%m-%d`]]" class="form-control" />
    </div>

    <div class="form-group">
        <label for="image_valid_enddate">[[%mediamanager.files.image_valid_enddate]] *</label>
        <input type="date" name="license[image_valid_enddate]" id="image_valid_enddate" value="[[+licensing.image_valid_enddate:date=`%Y-%m-%d`]]" class="form-control" />
    </div>

    <div class="form-group">
        <label>[[%mediamanager.files.license_exists]] *</label>
        <div class="radio">
            <label>
                <input type="radio" name="license[license_exists]" value="0" [[+licensing.license_exists:neq=`1`:then=`checked="checked"`:else=``]]>
                [[%mediamanager.global.no]]
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="license[license_exists]" value="1" [[+licensing.license_exists:eq=`1`:then=`checked="checked"`:else=``]]>
                [[%mediamanager.global.yes]]
            </label>
        </div>
    </div>

    <div class="form-group">
        <label>[[%mediamanager.files.license_depicted_consent]] *</label>
        <div class="radio">
            <label>
                <input type="radio" name="license[license_depicted_consent]" value="0" [[+licensing.license_depicted_consent:neq=`1`:then=`checked="checked"`:else=``]]>
                [[%mediamanager.global.no]]
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="license[license_depicted_consent]" value="1" [[+licensing.license_depicted_consent:eq=`1`:then=`checked="checked"`:else=``]]>
                [[%mediamanager.global.yes]]
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="license_file">[[%mediamanager.files.license_file]]</label>
        [[+licensing.license_path:notempty=`
            <p>
                <a href="[[+licensing.license_path]]" target="_blank">[[+licensing.license_path]]</a>
            </p>
            <br/>
        `:isempty=``]]

        <input type="file" name="license_file" id="license_file" class="form-control"/>
        <small>[[%mediamanager.files.license_file_help? &extensions=`[[+licensing.license_file_extensions]]`]]</small>
    </div>
</div>