<li id="items_[[+id]]">
    <div class="ui-sortable-handle">
        [[+name]]
        <span class="pull-right">
            <a href="javascript:void(0)" data-edit-category="[[+id]]" data-edit-name="[[+name]]" data-edit-sources="[[+sources]]" data-edit-message="[[+editMessage]]" data-edit-title="[[+editTitle]]" data-edit-confirm="[[+editConfirm]]" data-edit-cancel="[[+editCancel]]">
                [[+edit]]
            </a>
            -
            <a href="javascript:void(0)" data-delete-category="[[+id]]" data-delete-message="[[+deleteMessage]]" data-delete-title="[[+deleteTitle]]" data-delete-confirm="[[+deleteConfirm]]" data-delete-cancel="[[+deleteCancel]]">
                [[+delete]]
            </a>
        </span>
    </div>

    [[+children:notempty=`<ol>[[+children]]</ol>`]]
</li>