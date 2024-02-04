{{Form::open(array('url'=>'roles','method'=>'post'))}}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'col-form-label'])}}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter Role Name')))}}
                    @error('name')
                    <span class="invalid-name text-danger text-xs" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="">

                    @if(!empty($permissions))
                        <div class="table-border-style">
                        <label for="permissions" class="col-form-label">{{__('Assign Permission to Roles')}}</label>
                        <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th width="10%">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input align-middle" name="checkall"  id="checkall" >
                                        </div>
                                    </th>
                                    <th width="10%" class="text-dark">{{__('Module')}}</th>
                                    <th class="text-dark ps-0">{{__('Permissions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $modules=['dashboard','client','advocate','document','doctype','member','group','court','highcourt','bench','cause','case','todo','bill','tax','diary','timesheet','expense','feereceived','calendar','motions','setting'];
                                @endphp
                                @foreach($modules as $module)
                                    <tr>
                                       <td width="10%">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input ischeck" name="checkall" data-id="{{str_replace(' ', '', $module)}}">
                                            </div>
                                       </td>
                                        <td width="10%">
                                            <label class="ischeck" data-id="{{str_replace(' ', '', $module)}}">{{ ucfirst($module) }}</label>
                                        </td>
                                        <td>
                                            <div class="row">
                                                @if(in_array('manage '.$module,(array) $permissions))
                                                    @if($key = array_search('manage '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Manage',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('create '.$module,(array) $permissions))
                                                    @if($key = array_search('create '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Create',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('duplicate '.$module,(array) $permissions))
                                                    @if($key = array_search('duplicate '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'duplicate',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('edit '.$module,(array) $permissions))
                                                    @if($key = array_search('edit '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Edit',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('delete '.$module,(array) $permissions))
                                                    @if($key = array_search('delete '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Delete',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('show '.$module,(array) $permissions))
                                                    @if($key = array_search('show '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Show',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('view '.$module,(array) $permissions))
                                                    @if($key = array_search('view '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Show',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('move '.$module,(array) $permissions))
                                                    @if($key = array_search('move '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Move',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('client permission',(array) $permissions))
                                                    @if($key = array_search('client permission '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Client Permission',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('invite user',(array) $permissions))
                                                    @if($key = array_search('invite user '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Invite User ',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('change password '.$module,(array) $permissions))
                                                    @if($key = array_search('change password '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[]',$key,false, ['class'=>'form-check-input isscheck isscheck_'.str_replace(' ', '', $module),'id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Change Password',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif
                                                @if(in_array('buy '.$module,(array) $permissions))
                                                    @if($key = array_search('buy '.$module,$permissions))
                                                        <div class="col-md-3 form-check">
                                                            {{Form::checkbox('permissions[] isscheck isscheck_'.str_replace(' ', '', $module),$key,false, ['class'=>'form-check-input','id' =>'permission'.$key])}}
                                                            {{Form::label('permission'.$key,'Buy',['class'=>'form-check-label'])}}<br>
                                                        </div>
                                                    @endif
                                                @endif

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn btn-primary ms-2">
    </div>
{{Form::close()}}

<script>
    $(document).ready(function () {
           $("#checkall").on('click',function(){
                $('input:checkbox').not(this).prop('checked', this.checked);
            });
           $(".ischeck").on('click',function(){
                var ischeck = $(this).data('id');
                $('.isscheck_'+ ischeck).prop('checked', this.checked);
            });
        });
</script>
