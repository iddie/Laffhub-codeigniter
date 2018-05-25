<section class="content">
    <h1 class="page-title">
        <small>Deactivate Contents</small>
    </h1>
    <div class="row">
        <div class="col-md-12">
            <div class="note note-danger">
                <p> NOTE: The below datatable is connected and deactivation occurs in real time</p>
            </div>
            <!-- Begin: Demo Datatable 1 -->
            <div class="portlet light portlet-fit portlet-datatable bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-settings font-dark"></i>
                        <span class="caption-subject font-dark sbold uppercase">Videos</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group btn-group-devided" data-toggle="buttons">
                            <label class="btn btn-transparent grey-salsa btn-outline btn-circle btn-sm active">
                                <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                            <label class="btn btn-transparent grey-salsa btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Settings</label>
                        </div>
                        <div class="btn-group">
                                                <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                                                    <i class="fa fa-share"></i>
                                                    <span class="hidden-xs"> Tools </span>
                                                    <i class="fa fa-angle-down"></i>
                                                </a>
                                                <ul class="dropdown-menu pull-right" id="datatable_ajax_tools">
                                                    <li>
                                                        <a href="javascript:;" data-action="0" class="tool-action">
                                                            <i class="icon-printer"></i> Print</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="1" class="tool-action">
                                                            <i class="icon-check"></i> Copy</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="2" class="tool-action">
                                                            <i class="icon-doc"></i> PDF</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="3" class="tool-action">
                                                            <i class="icon-paper-clip"></i> Excel</a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="4" class="tool-action">
                                                            <i class="icon-cloud-upload"></i> CSV</a>
                                                    </li>
                                                    <li class="divider"> </li>
                                                    <li>
                                                        <a href="javascript:;" data-action="5" class="tool-action">
                                                            <i class="icon-refresh"></i> Reload</a>
                                                    </li>
                                                    </li>
                                                </ul>
                                            </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="table-container">
                        <div class="table-actions-wrapper">
                            <span> </span>
                            <select class="table-group-action-input form-control input-inline input-small input-sm">
                                <option value="">Select...</option>
                                <option value="Cancel">Cancel</option>
                                <option value="Cancel">Hold</option>
                                <option value="Cancel">On Hold</option>
                                <option value="Close">Close</option>
                            </select>
                            <button class="btn btn-sm green table-group-action-submit">
                                <i class="fa fa-check"></i> Submit</button>
                        </div>
                        <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                            <thead>
                                <tr role="row" class="heading">
                                    <th width="2%">
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes" />
                                            <span></span>
                                        </label>
                                    </th>
                                    <th width="10">ID</th>
                                    <th width="5%"> Publisher&nbsp;# </th>
                                    <th width="15%">Title</th>
                                    <th width="200"> Category </th>
                                    <th width="100">Comedian </th>
                                    <th width="10%"> Video&nbsp;Status </th>
                                    <th width="10%">Play Status</th>
                                    <th width="10%"> Date </th>
                                    <th width="40">Featured</th>
                                    <th width="10%"> Actions </th>
                                </tr>
                                <tr role="row" class="filter">
                                    <td> </td>
                                    <td>
                                        <input type="text" class="form-control form-filter input-sm" name="id"> </td>
                                    
                                    <td>
                                        <input type="text" class="form-control form-filter input-sm" name="publisher_email"> </td>
                                    <td>
                                    <input type="text" class="form-control form-filter input-sm" name="video_title"> </td>
                                    <td>
                                        <input type="text" class="form-control form-filter input-sm" name="video_category"> </td>
                                        <td>
                                        <input type="text" class="form-control form-filter input-sm" name="comedian"> </td>
                                    <td>
                                        <input type="text" class="form-control form-filter input-sm" name="video_status"> </td>
                                    <td>
                                            <input type="text" class="form-control form-filter input-sm" name="play_status"  /> </div>
                                    </td>
                                    <td>
                                    <div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
                                                                <input type="text" class="form-control form-filter input-sm" readonly name="order_date_from" placeholder="From">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-sm default" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                                                                <input type="text" class="form-control form-filter input-sm" readonly name="order_date_to" placeholder="To">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-sm default" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                            </td>
                                    <td>
                                        <select name="featured" class="form-control form-filter input-sm">
                                            <option value="">Select...</option>
                                            <option value="pending">Yes</option>
                                            <option value="pending">No</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="margin-bottom-5">
                                            <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                                <i class="fa fa-search"></i> Search</button>
                                        </div>
                                        <button class="btn btn-sm red btn-outline filter-cancel">
                                            <i class="fa fa-times"></i> Reset</button>
                                    </td>
                                </tr>
                            </thead>
                            <tbody> </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End: Demo Datatable 1 -->
        </div>
    </div>
</section>
