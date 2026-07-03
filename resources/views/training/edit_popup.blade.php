{{ Form::open(['url' => route('training.update-training', $training->id), 'method' => 'PUT', 'id' => 'editTrainingForm']) }}
<div class="modal-body">
    <div class="row">

        <div class="form-group">
            {{ Form::label('training_type_id', __('Training Type'), ['class' => 'form-label']) }}
            {{ Form::select('training_type_id', $trainingTypes, $training->training_type_id, ['class' => 'form-control', 'id' => 'edit_training_type_select']) }}
        </div>

        <div class="form-group mt-3">
            {{ Form::label('training_course_id', __('Training Course'), ['class' => 'form-label']) }}
            <select name="training_course_id" id="edit_training_course_select" class="form-control">
                @foreach($trainingCourses as $course)
                    <option value="{{ $course->id }}" {{ $training->training_course_id == $course->id ? 'selected' : '' }}>
                        {{ $course->name }} ({{ $course->duration }} days)
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            {{ Form::label('companyName', __('Company'), ['class' => 'form-label']) }}
            {{ Form::select('companyName', ['' => 'Select a Company'] + $contractTypes->map(fn($c) => strtoupper($c))->toArray(), $training->companyName, ['class' => 'form-control', 'required' => 'required', 'id' => 'edit_company_select']) }}
        </div>

        <div class="form-group mt-3">
            {{ Form::label('group_id', __('Group'), ['class' => 'form-label']) }}
            <div id="edit_group_checkbox_container">
                @foreach($groups as $id => $name)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input edit-group-checkbox" type="checkbox"
                                name="group_id[]" value="{{ $id }}" id="edit_group_{{ $id }}"
                                {{ in_array($id, $assignedGroupIds) ? 'checked' : '' }}>
                            <label class="form-check-label" for="edit_group_{{ $id }}">{{ strtoupper($name) }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group mt-3">
            {{ Form::label('driver_id', __('Select Drivers'), ['class' => 'form-label']) }}
            <div class="form-check mb-2">
                <input class="form-check-input" type="checkbox" id="edit_select_all_drivers">
                <label class="form-check-label" for="edit_select_all_drivers">{{ __('Select All Drivers') }}</label>
            </div>
            <div id="edit_driver_checkbox_container">
                @foreach($drivers as $id => $name)
                    <div class="form-check">
                        <input class="form-check-input edit-driver-checkbox" type="checkbox"
                            name="driver_id[]" value="{{ $id }}" id="edit_driver_{{ $id }}"
                            {{ in_array($id, $assignedDriverIds) ? 'checked' : '' }}>
                        <label class="form-check-label driver-name" for="edit_driver_{{ $id }}">{{ strtoupper($name) }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-group mt-3">
            {{ Form::label('from_date', __('From Date'), ['class' => 'form-label']) }}
            {{ Form::date('from_date', $training->from_date, ['class' => 'form-control', 'required' => 'required']) }}
        </div>

        <div class="form-group mt-3">
            {{ Form::label('to_date', __('To Date'), ['class' => 'form-label']) }}
            {{ Form::date('to_date', $training->to_date, ['class' => 'form-control', 'required' => 'required']) }}
        </div>

        <div class="form-group mt-3">
            {{ Form::label('from_time', __('From Time'), ['class' => 'form-label']) }}
            {{ Form::time('from_time', $training->from_time, ['class' => 'form-control', 'required' => 'required']) }}
        </div>

        <div class="form-group mt-3">
            {{ Form::label('to_time', __('To Time'), ['class' => 'form-label']) }}
            {{ Form::time('to_time', $training->to_time, ['class' => 'form-control', 'required' => 'required']) }}
        </div>

        <div class="form-group mt-3">
            {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
            {{ Form::textarea('description', $training->description, ['class' => 'form-control']) }}
        </div>

    </div>
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Update') }}" class="btn btn-primary" id="edit-submit-btn">
    <div id="edit_loader" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(194,194,194,0.8); z-index:9999;">
        <div style="background:rgba(255,255,255,0.8); box-shadow:0 0 10px rgba(0,0,0,0.2); border-radius:10px; position:absolute; top:50%; left:50%; padding:10px;">
            <div class="spinner-border text-primary" role="status" style="width:3rem; height:3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</div>
{{ Form::close() }}

<script>
$(document).ready(function () {

    // Training type change → reload courses
    $('#edit_training_type_select').change(function () {
        let trainingTypeId = $(this).val();
        $('#edit_training_course_select').empty();
        if (trainingTypeId) {
            $.ajax({
                url: '{{ route('training.courses') }}',
                method: 'GET',
                data: { training_type_id: trainingTypeId },
                success: function (response) {
                    $.each(response.courses, function (index, course) {
                        $('#edit_training_course_select').append($('<option>', {
                            value: course.id,
                            text: course.name + ' (' + course.duration + ' days)'
                        }));
                    });
                }
            });
        }
    });

    // Company change → reload groups and clear drivers
    $('#edit_company_select').change(function () {
        let companyId = $(this).val();
        $('#edit_group_checkbox_container').empty();
        $('#edit_driver_checkbox_container').empty();

        if (companyId) {
            $.ajax({
                url: '{{ route('groups.byCompany') }}',
                method: 'GET',
                data: { company_id: companyId },
                success: function (response) {
                    $.each(response.groups, function (index, group) {
                        let checkbox = `
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input edit-group-checkbox" type="checkbox" name="group_id[]" value="${index}" id="edit_group_${index}">
                                    <label class="form-check-label" for="edit_group_${index}">${group}</label>
                                </div>
                            </div>`;
                        $('#edit_group_checkbox_container').append(checkbox);
                    });
                    attachGroupChangeEvent(companyId);
                }
            });
        }
    });

    // Group change → reload drivers
    function attachGroupChangeEvent(companyId) {
        $('.edit-group-checkbox').off('change').on('change', function () {
            let groupIds = $('.edit-group-checkbox:checked').map(function () { return $(this).val(); }).get();
            $('#edit_driver_checkbox_container').empty();

            if (groupIds.length > 0) {
                $.ajax({
                    url: '{{ route('drivers.byGroup') }}',
                    method: 'GET',
                    data: { company_id: companyId, group_id: groupIds },
                    success: function (response) {
                        $.each(response.drivers, function (index, driver) {
                            let driverCheckbox = `
                                <div class="form-check">
                                    <input class="form-check-input edit-driver-checkbox" type="checkbox" name="driver_id[]" value="${index}" id="edit_driver_${index}">
                                    <label class="form-check-label driver-name" for="edit_driver_${index}">${driver}</label>
                                </div>`;
                            $('#edit_driver_checkbox_container').append(driverCheckbox);
                        });
                    }
                });
            }
        });
    }

    // Attach group change for pre-loaded groups
    attachGroupChangeEvent($('#edit_company_select').val());

    // Select all drivers
    $('#edit_select_all_drivers').change(function () {
        $('.edit-driver-checkbox').prop('checked', $(this).is(':checked'));
    });

    $('#edit_driver_checkbox_container').on('change', '.edit-driver-checkbox', function () {
        if (!$(this).is(':checked')) {
            $('#edit_select_all_drivers').prop('checked', false);
        } else if ($('.edit-driver-checkbox:checked').length === $('.edit-driver-checkbox').length) {
            $('#edit_select_all_drivers').prop('checked', true);
        }
    });

    // Submit loader
    $('#editTrainingForm').on('submit', function () {
        $('#edit-submit-btn').prop('disabled', true);
        $('#edit_loader').show();
    });
});
</script>

<style>
#edit_group_checkbox_container { display: flex; flex-wrap: wrap; }
#edit_group_checkbox_container .form-check { width: 30%; margin-bottom: 10px; }
#edit_driver_checkbox_container { display: flex; flex-wrap: wrap; }
#edit_driver_checkbox_container .form-check { width: 50%; margin-bottom: 10px; }
.driver-name { text-transform: uppercase; }
</style>
