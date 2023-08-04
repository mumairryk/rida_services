@extends('admin.template.layout')
@section('content')
@php
$privileges = \App\Models\UserPrivileges::join('users', 'users.id', 'user_privileges.user_id')
->join('admin_designation', 'admin_designation.id', '=', 'users.designation_id')->where(['users.id' => $id, 'user_privileges.designation_id' => \App\Models\User::where('id', $id)->pluck('designation_id')->first()])->pluck('privileges')->first();
$privileges = json_decode($privileges, true);
@endphp
<div class="card">
   <div class="card-body">
      <div class="col-xs-12 col-sm-12">
         <form method="post" id="admin-form" action="{{ url('admin/save_privilege') }}" enctype="multipart/form-data"
            data-parsley-validate="true">
            <input type="hidden" name="id" value="{{ $id }}">
            @csrf()
            <div class="form-group">
               <fieldset>
                  <legend>Access Rights</legend>
                  <div class="form-group row mt-0 mb-3">
                     <label class="col-sm-2 col-form-label">Admin Users</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input adminusers" name="access_groups[adminusers][View]" @if( isset($privileges['adminusers']['View']) && $privileges['adminusers']['View'] == 1 )
                                 checked
                                 @endif value="1"> View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input adminusers" name="access_groups[adminusers][Create]" @if( isset($privileges['adminusers']['Create']) && $privileges['adminusers']['Create'] == 1 )
                                 checked
                                 @endif value="1"> Create                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input adminusers" name="access_groups[adminusers][Edit]" @if( isset($privileges['adminusers']['Edit']) && $privileges['adminusers']['Edit'] == 1 )
                                 checked
                                 @endif value="1"> Edit                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input adminusers" name="access_groups[adminusers][ChangePassword]" @if( isset($privileges['adminusers']['ChangePassword']) && $privileges['adminusers']['ChangePassword'] == 1 )
                                checked
                                @endif value="1"> Change Password                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input adminusers" name="access_groups[adminusers][UpdatePermission]" @if( isset($privileges['adminusers']['UpdatePermission']) && $privileges['adminusers']['UpdatePermission'] == 1 )
                                checked
                                @endif value="1"> Update Permission                                                        <i class="input-helper"></i></label>
                             </div>
                              <div class="form-check form-check-inline mr-5">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input adminusers" name="access_groups[adminusers][Delete]" @if( isset($privileges['adminusers']['Delete']) && $privileges['adminusers']['Delete'] == 1 )
                                 checked
                                 @endif value="1"> Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="adminusers">Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="adminusers">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Admin User Designation</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input admin_user_desig" name="access_groups[admin_user_desig][View]" @if( isset($privileges['admin_user_desig']['View']) && $privileges['admin_user_desig']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input admin_user_desig" name="access_groups[admin_user_desig][Create]" @if( isset($privileges['admin_user_desig']['Create']) && $privileges['admin_user_desig']['Create'] == 1 )
                                 checked
                                 @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input admin_user_desig" name="access_groups[admin_user_desig][Edit]" @if( isset($privileges['admin_user_desig']['Edit']) && $privileges['admin_user_desig']['Edit'] == 1 )
                                 checked
                                 @endif value="1" > Edit                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input admin_user_desig" name="access_groups[admin_user_desig][Delete]" @if( isset($privileges['admin_user_desig']['Delete']) && $privileges['admin_user_desig']['Delete'] == 1 )
                                 checked
                                 @endif value="1" > Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="admin_user_desig">Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="admin_user_desig">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Customers</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input customers" name="access_groups[customers][View]" @if( isset($privileges['customers']['View']) && $privileges['customers']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input customers" name="access_groups[customers][Create]" @if( isset($privileges['customers']['Create']) && $privileges['customers']['Create'] == 1 )
                                 checked
                                 @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input customers" name="access_groups[customers][Edit]" @if( isset($privileges['customers']['Edit']) && $privileges['customers']['Edit'] == 1 )
                                 checked
                                 @endif value="1" > Edit                                                        <i class="input-helper"></i></label>
                              </div>

                              <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input customers" name="access_groups[customers][ChangePassword]" @if( isset($privileges['customers']['ChangePassword']) && $privileges['customers']['ChangePassword'] == 1 )
                                checked
                                @endif value="1" > Change Password                                                        <i class="input-helper"></i></label>
                             </div>


                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input customers" name="access_groups[customers][Delete]" @if( isset($privileges['customers']['Delete']) && $privileges['customers']['Delete'] == 1 )
                                 checked
                                 @endif value="1" > Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="customers">Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="customers">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-3">
                     <label class="col-sm-2 col-form-label">Vendors</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input vendor" name="access_groups[vendor][View]" @if( isset($privileges['vendor']['View']) && $privileges['vendor']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input vendor" name="access_groups[vendor][Create]" @if( isset($privileges['vendor']['Create']) && $privileges['vendor']['Create'] == 1 )
                                 checked
                                 @endif value="1"> Create                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input vendor" name="access_groups[vendor][Edit]" @if( isset($privileges['vendor']['Edit']) && $privileges['vendor']['Edit'] == 1 )
                                 checked
                                 @endif value="1"> Edit                                                        <i class="input-helper"></i></label>
                              </div>



                              <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input vendor" name="access_groups[vendor][ChangePassword]" @if( isset($privileges['vendor']['ChangePassword']) && $privileges['vendor']['ChangePassword'] == 1 )
                                checked
                                @endif value="1"> Change Password                                                        <i class="input-helper"></i></label>
                             </div>
                           



                              <div class="form-check form-check-inline mr-5">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input vendor" name="access_groups[vendor][Delete]" @if( isset($privileges['vendor']['Delete']) && $privileges['vendor']['Delete'] == 1 )
                                 checked
                                 @endif value="1"> Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="vendor" >Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="vendor">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Stores</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input stores" name="access_groups[stores][View]" @if( isset($privileges['stores']['View']) && $privileges['stores']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input stores" name="access_groups[stores][Create]" @if( isset($privileges['stores']['Create']) && $privileges['stores']['Create'] == 1 )
                                 checked
                                 @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input stores" name="access_groups[stores][Edit]" @if( isset($privileges['stores']['Edit']) && $privileges['stores']['Edit'] == 1 )
                                 checked
                                 @endif value="1" > Edit                                                        <i class="input-helper"></i></label>
                              </div>

                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input stores" name="access_groups[stores][Delete]" @if( isset($privileges['stores']['Delete']) && $privileges['stores']['Delete'] == 1 )
                                 checked
                                 @endif value="1" > Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="stores">Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="stores">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Products</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input products" name="access_groups[products][View]" @if( isset($privileges['products']['View']) && $privileges['products']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input products" name="access_groups[products][Create]" @if( isset($privileges['products']['Create']) && $privileges['products']['Create'] == 1 )
                                 checked
                                 @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                              </div>

                              <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input products" name="access_groups[products][ImportExport]" @if( isset($privileges['products']['ImportExport']) && $privileges['products']['ImportExport'] == 1 )
                                checked
                                @endif value="1" > Import/Export                                                        <i class="input-helper"></i></label>
                             </div>

                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input products" name="access_groups[products][Edit]" @if( isset($privileges['products']['Edit']) && $privileges['products']['Edit'] == 1 )
                                 checked
                                 @endif value="1" > Edit                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input products" name="access_groups[products][Delete]" @if( isset($privileges['products']['Delete']) && $privileges['products']['Delete'] == 1 )
                                 checked
                                 @endif value="1" > Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="products">Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="products">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Coupon Codes</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input coupon" name="access_groups[coupon][View]" @if( isset($privileges['coupon']['View']) && $privileges['coupon']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input coupon" name="access_groups[coupon][Create]" @if( isset($privileges['coupon']['Create']) && $privileges['coupon']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input coupon" name="access_groups[coupon][Edit]" @if( isset($privileges['coupon']['Edit']) && $privileges['adminusers']['Edit'] == 1 )
                                checked
                                @endif value="1" > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input coupon" name="access_groups[coupon][Delete]" @if( isset($privileges['coupon']['Delete']) && $privileges['coupon']['Delete'] == 1 )
                                checked
                                @endif value="1" > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="coupon">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="coupon">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                  <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Orders</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input orders" name="access_groups[orders][View]" @if( isset($privileges['orders']['View']) && $privileges['orders']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>

                              <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input orders" name="access_groups[orders][Details]" @if( isset($privileges['orders']['Details']) && $privileges['orders']['Details'] == 1 )
                                checked
                                @endif value="1" > Details                                                        <i class="input-helper"></i></label>
                             </div>

                           </div>
                           <div class="col-4 pt-1">
                            <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="orders">Set All</button>
                            <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="orders">Reset</button>
                         </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Category</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input category" name="access_groups[category][View]" @if( isset($privileges['category']['View']) && $privileges['category']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input category" name="access_groups[category][Create]" @if( isset($privileges['category']['Create']) && $privileges['category']['Create'] == 1 )
                                 checked
                                 @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input category" name="access_groups[category][Edit]" @if( isset($privileges['category']['Edit']) && $privileges['category']['Edit'] == 1 )
                                 checked
                                 @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input category" name="access_groups[category][Delete]" @if( isset($privileges['category']['Delete']) && $privileges['category']['Delete'] == 1 )
                                 checked
                                 @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="category">Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="category">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Brand</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input brand" name="access_groups[brand][View]" @if( isset($privileges['brand']['View']) && $privileges['brand']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input brand" name="access_groups[brand][Create]" @if( isset($privileges['brand']['Create']) && $privileges['brand']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input brand" name="access_groups[brand][Edit]" @if( isset($privileges['brand']['Edit']) && $privileges['brand']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input brand" name="access_groups[brand][Delete]" @if( isset($privileges['brand']['Delete']) && $privileges['brand']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="brand">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="brand">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Product Attribute</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input attribute" name="access_groups[attribute][View]" @if( isset($privileges['attribute']['View']) && $privileges['attribute']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input attribute" name="access_groups[attribute][Create]" @if( isset($privileges['attribute']['Create']) && $privileges['attribute']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input attribute" name="access_groups[attribute][Edit]" @if( isset($privileges['attribute']['Edit']) && $privileges['attribute']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>

                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input attribute" name="access_groups[attribute][attr_values]" @if( isset($privileges['attribute']['attr_values']) && $privileges['attribute']['attr_values'] == 1 )
                                checked
                                @endif value="1"  > Attribute Values                                                        <i class="input-helper"></i></label>
                             </div>

                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input attribute" name="access_groups[attribute][Delete]" @if( isset($privileges['attribute']['Delete']) && $privileges['attribute']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="attribute">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="attribute">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Industry Types</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input industry" name="access_groups[industry][View]" @if( isset($privileges['industry']['View']) && $privileges['industry']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input industry" name="access_groups[industry][Create]" @if( isset($privileges['industry']['Create']) && $privileges['industry']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input industry" name="access_groups[industry][Edit]" @if( isset($privileges['industry']['Edit']) && $privileges['industry']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input industry" name="access_groups[industry][Delete]" @if( isset($privileges['industry']['Delete']) && $privileges['industry']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="industry">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="industry">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Store Type</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input store_type" name="access_groups[store_type][View]" @if( isset($privileges['store_type']['View']) && $privileges['store_type']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input store_type" name="access_groups[store_type][Create]" @if( isset($privileges['store_type']['Create']) && $privileges['store_type']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input store_type" name="access_groups[store_type][Edit]" @if( isset($privileges['store_type']['Edit']) && $privileges['store_type']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input store_type" name="access_groups[store_type][Delete]" @if( isset($privileges['store_type']['Delete']) && $privileges['store_type']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="store_type">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="store_type">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Country</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input country" name="access_groups[country][View]" @if( isset($privileges['country']['View']) && $privileges['country']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input country" name="access_groups[country][Create]" @if( isset($privileges['country']['Create']) && $privileges['country']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input country" name="access_groups[country][Edit]" @if( isset($privileges['country']['Edit']) && $privileges['country']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input country" name="access_groups[country][Delete]" @if( isset($privileges['country']['Delete']) && $privileges['country']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="country">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="country">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">States</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input states" name="access_groups[states][View]" @if( isset($privileges['states']['View']) && $privileges['states']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input states" name="access_groups[states][Create]" @if( isset($privileges['states']['Create']) && $privileges['states']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input states" name="access_groups[states][Edit]" @if( isset($privileges['states']['Edit']) && $privileges['states']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input states" name="access_groups[states][Delete]" @if( isset($privileges['states']['Delete']) && $privileges['states']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="states">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="states">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Cities</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cities" name="access_groups[cities][View]" @if( isset($privileges['cities']['View']) && $privileges['cities']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cities" name="access_groups[cities][Create]" @if( isset($privileges['cities']['Create']) && $privileges['cities']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cities" name="access_groups[cities][Edit]" @if( isset($privileges['cities']['Edit']) && $privileges['cities']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cities" name="access_groups[cities][Delete]" @if( isset($privileges['cities']['Delete']) && $privileges['cities']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="cities">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="cities">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Bank</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input bank" name="access_groups[bank][View]" @if( isset($privileges['bank']['View']) && $privileges['bank']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input bank" name="access_groups[bank][Create]" @if( isset($privileges['bank']['Create']) && $privileges['bank']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input bank" name="access_groups[bank][Edit]" @if( isset($privileges['bank']['Edit']) && $privileges['bank']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input bank" name="access_groups[bank][Delete]" @if( isset($privileges['bank']['Delete']) && $privileges['bank']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="bank">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="bank">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Moda Categories</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input moda_categories" name="access_groups[moda_categories][View]" @if( isset($privileges['moda_categories']['View']) && $privileges['moda_categories']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input moda_categories" name="access_groups[moda_categories][Create]" @if( isset($privileges['moda_categories']['Create']) && $privileges['moda_categories']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input moda_categories" name="access_groups[moda_categories][Edit]" @if( isset($privileges['moda_categories']['Edit']) && $privileges['moda_categories']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input moda_categories" name="access_groups[moda_categories][Delete]" @if( isset($privileges['moda_categories']['Delete']) && $privileges['moda_categories']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="moda_categories">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="moda_categories">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Skin Colors</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input skin_colors" name="access_groups[skin_colors][View]" @if( isset($privileges['skin_colors']['View']) && $privileges['skin_colors']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input skin_colors" name="access_groups[skin_colors][Create]" @if( isset($privileges['skin_colors']['Create']) && $privileges['skin_colors']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input skin_colors" name="access_groups[skin_colors][Edit]" @if( isset($privileges['skin_colors']['Edit']) && $privileges['skin_colors']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input skin_colors" name="access_groups[skin_colors][Delete]" @if( isset($privileges['skin_colors']['Delete']) && $privileges['skin_colors']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="skin_colors">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="skin_colors">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Hair Colors</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hair_colors" name="access_groups[hair_colors][View]" @if( isset($privileges['hair_colors']['View']) && $privileges['hair_colors']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hair_colors" name="access_groups[hair_colors][Create]" @if( isset($privileges['hair_colors']['Create']) && $privileges['hair_colors']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hair_colors" name="access_groups[hair_colors][Edit]" @if( isset($privileges['hair_colors']['Edit']) && $privileges['hair_colors']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hair_colors" name="access_groups[hair_colors][Delete]" @if( isset($privileges['hair_colors']['Delete']) && $privileges['hair_colors']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="hair_colors">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="hair_colors">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Public Business Info</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input public_business_info" name="access_groups[public_business_info][View]" @if( isset($privileges['public_business_info']['View']) && $privileges['public_business_info']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input public_business_info" name="access_groups[public_business_info][Create]" @if( isset($privileges['public_business_info']['Create']) && $privileges['public_business_info']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input public_business_info" name="access_groups[public_business_info][Edit]" @if( isset($privileges['public_business_info']['Edit']) && $privileges['public_business_info']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input public_business_info" name="access_groups[public_business_info][Delete]" @if( isset($privileges['public_business_info']['Delete']) && $privileges['public_business_info']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="public_business_info">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="public_business_info">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Hash Tags</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hash_tags" name="access_groups[hash_tags][View]" @if( isset($privileges['hash_tags']['View']) && $privileges['hash_tags']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hash_tags" name="access_groups[hash_tags][Create]" @if( isset($privileges['hash_tags']['Create']) && $privileges['hash_tags']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hash_tags" name="access_groups[hash_tags][Edit]" @if( isset($privileges['hash_tags']['Edit']) && $privileges['hash_tags']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input hash_tags" name="access_groups[hash_tags][Delete]" @if( isset($privileges['hash_tags']['Delete']) && $privileges['hash_tags']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="hash_tags">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="hash_tags">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                  {{-- <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Banners</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input banners" name="access_groups[banners][View]" @if( isset($privileges['banners']['View']) && $privileges['banners']['View'] == 1 )
                                 checked
                                 @endif value="1" > View                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input banners" name="access_groups[banners][Create]" @if( isset($privileges['banners']['Create']) && $privileges['banners']['Create'] == 1 )
                                 checked
                                 @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input banners" name="access_groups[banners][Edit]" @if( isset($privileges['banners']['Edit']) && $privileges['banners']['Edit'] == 1 )
                                 checked
                                 @endif value="1" > Edit                                                        <i class="input-helper"></i></label>
                              </div>
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input banners" name="access_groups[banners][Delete]" @if( isset($privileges['banners']['Delete']) && $privileges['banners']['Delete'] == 1 )
                                 checked
                                 @endif value="1" > Delete                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                              <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="banners">Set All</button>
                              <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="banners">Reset</button>
                           </div>
                        </div>
                     </div>
                  </div> --}}

                  <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">CMS Pages</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cms" name="access_groups[cms][View]" @if( isset($privileges['cms']['View']) && $privileges['cms']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cms" name="access_groups[cms][Create]" @if( isset($privileges['cms']['Create']) && $privileges['cms']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cms" name="access_groups[cms][Edit]" @if( isset($privileges['cms']['Edit']) && $privileges['cms']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cms" name="access_groups[cms][Delete]" @if( isset($privileges['cms']['Delete']) && $privileges['cms']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="cms">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="cms">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">FAQ</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input faq" name="access_groups[faq][View]" @if( isset($privileges['faq']['View']) && $privileges['faq']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input faq" name="access_groups[faq][Create]" @if( isset($privileges['faq']['Create']) && $privileges['faq']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input faq" name="access_groups[faq][Edit]" @if( isset($privileges['faq']['Edit']) && $privileges['faq']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input faq" name="access_groups[faq][Delete]" @if( isset($privileges['faq']['Delete']) && $privileges['faq']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="faq">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="faq">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Help</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input help" name="access_groups[help][View]" @if( isset($privileges['help']['View']) && $privileges['help']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input help" name="access_groups[help][Create]" @if( isset($privileges['help']['Create']) && $privileges['help']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input help" name="access_groups[help][Edit]" @if( isset($privileges['help']['Edit']) && $privileges['help']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input help" name="access_groups[help][Delete]" @if( isset($privileges['help']['Delete']) && $privileges['help']['Delete'] == 1 )
                                checked
                                @endif value="1"  > Delete                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="help">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="help">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Contact Details</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input contact_settings" name="access_groups[contact_settings][Edit]" @if( isset($privileges['contact_settings']['Edit']) && $privileges['contact_settings']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="contact_settings">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="contact_settings">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Settings</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input settings" name="access_groups[settings][Edit]" @if( isset($privileges['settings']['Edit']) && $privileges['settings']['Edit'] == 1 )
                                checked
                                @endif value="1"  > Edit                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="settings">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="settings">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Customer Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input cust_rep" name="access_groups[cust_rep][View]" @if( isset($privileges['cust_rep']['View']) && $privileges['cust_rep']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="cust_rep">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="cust_rep">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Vendors Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input vendor_rep" name="access_groups[vendor_rep][View]" @if( isset($privileges['vendor_rep']['View']) && $privileges['vendor_rep']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="vendor_rep">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="vendor_rep">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Store Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input store_rep" name="access_groups[store_rep][View]" @if( isset($privileges['store_rep']['View']) && $privileges['store_rep']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="store_rep">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="store_rep">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Order Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input order_rep" name="access_groups[order_rep][View]" @if( isset($privileges['order_rep']['View']) && $privileges['order_rep']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="order_rep">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="order_rep">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Commission Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input commission_rep" name="access_groups[commission_rep][View]" @if( isset($privileges['commission_rep']['View']) && $privileges['commission_rep']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="commission_rep">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="commission_rep">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Out Of stock Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input out_of_stock_rep" name="access_groups[out_of_stock_rep][View]" @if( isset($privileges['out_of_stock_rep']['View']) && $privileges['out_of_stock_rep']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="out_of_stock_rep">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="out_of_stock_rep">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>
                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Blocked Users Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input blocked_user" name="access_groups[customers][BlockedUsers]" @if( isset($privileges['customers']['BlockedUsers']) && $privileges['customers']['BlockedUsers'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="blocked_user">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="blocked_user">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Reported Users Report</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input reported_user" name="access_groups[customers][ReportedUsers]" @if( isset($privileges['customers']['ReportedUsers']) && $privileges['customers']['ReportedUsers'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>
                          </div>
                          <div class="col-4 pt-1">
                             <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="reported_user">Set All</button>
                             <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="reported_user">Reset</button>
                          </div>
                       </div>
                    </div>
                 </div>

                 <div class="form-group row mt-0 mb-0">
                    <label class="col-sm-2 col-form-label">Notification</label>
                    <div class="col-sm-10">
                       <div class="row">
                          <div class="col-8" role="access-group-row">
                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input notifications" name="access_groups[notification][View]" @if( isset($privileges['notification']['View']) && $privileges['notification']['View'] == 1 )
                                checked
                                @endif value="1" > View                                                        <i class="input-helper"></i></label>
                             </div>

                             <div class="form-check form-check-inline mr-5 ">
                                <label class="form-check-label">
                                <input type="checkbox" class="form-check-input notifications" name="access_groups[notification][Create]" @if( isset($privileges['notification']['Create']) && $privileges['notification']['Create'] == 1 )
                                checked
                                @endif value="1" > Create                                                        <i class="input-helper"></i></label>
                             </div>
                 
                             <div class="form-check form-check-inline mr-5 ">
                               <label class="form-check-label">
                               <input type="checkbox" class="form-check-input notifications" name="access_groups[notification][Delete]" @if( isset($privileges['notification']['Delete']) && $privileges['notification']['Delete'] == 1 )
                               checked
                               @endif value="1" > Delete                                                        <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-4 pt-1">
                            <button type="button" class="btn btn-mini btn-outline-success" role="access-set-all" target="notifications">Set All</button>
                            <button type="button" class="btn btn-mini btn-outline-warning ml-2" role="access-reset-all" target="notifications">Reset</button>
                         </div>
                       </div>
                    </div>
                 </div>
                  
                  {{-- <div class="form-group row mt-0 mb-0">
                     <label class="col-sm-2 col-form-label">Reports</label>
                     <div class="col-sm-10">
                        <div class="row">
                           <div class="col-8" role="access-group-row">
                              <div class="form-check form-check-inline mr-5 ">
                                 <label class="form-check-label">
                                 <input type="checkbox" class="form-check-input" name="access_groups[report][View]" value="1" @if( isset($privileges['report']['View']) && $privileges['report']['View'] == 1 )
                                 checked
                                 @endif data-parsley-multiple="access_groups1041" > View                                                        <i class="input-helper"></i></label>
                              </div>
                           </div>
                           <div class="col-4 pt-1">
                           </div>
                        </div>
                     </div>
                  </div> --}}
               </fieldset>
            </div>
            <div class="form-group">
               <button type="submit" class="btn btn-primary">Submit</button>
            </div>
         </form>
      </div>
      <div class="col-xs-12 col-sm-6">
      </div>
   </div>
</div>
@stop
@section('script')
<script>
   App.initFormView();
   $('body').off('submit', '#admin-form');
   $('body').on('submit', '#admin-form', function(e) {
       e.preventDefault();
       var $form = $(this);
       var formData = new FormData(this);
       $(".invalid-feedback").remove();
   
       App.loading(true);
       $form.find('button[type="submit"]')
           .text('Saving')
           .attr('disabled', true);
   
       $.ajax({
           type: "POST",
           enctype: 'multipart/form-data',
           url: $form.attr('action'),
           data: formData,
           processData: false,
           contentType: false,
           cache: false,
           dataType: 'json',
           timeout: 600000,
           success: function(res) {
               App.loading(false);
   
               if (res['status'] == 0) {
                   if (typeof res['errors'] !== 'undefined') {
                       var error_def = $.Deferred();
                       var error_index = 0;
                       jQuery.each(res['errors'], function(e_field, e_message) {
                           if (e_message != '') {
                               $('[name="' + e_field + '"]').eq(0).addClass('is-invalid');
                               $('<div class="invalid-feedback">' + e_message + '</div>')
                                   .insertAfter($('[name="' + e_field + '"]').eq(0));
                               if (error_index == 0) {
                                   error_def.resolve();
                               }
                               error_index++;
                           }
                       });
                       error_def.done(function() {
                           var error = $form.find('.is-invalid').eq(0);
                           $('html, body').animate({
                               scrollTop: (error.offset().top - 100),
                           }, 500);
                       });
                   } else {
                       var m = res['message'];
                       App.alert(m, 'Oops!');
                   }
               } else {
                   App.alert(res['message']);
                   setTimeout(function() {
                       window.location.href = App.siteUrl('/admin/admin_users/update_permission/{{ $id }}');
                   }, 1500);
               }
   
               $form.find('button[type="submit"]')
                   .text('Save')
                   .attr('disabled', false);
           },
           error: function(e) {
               App.loading(false);
               $form.find('button[type="submit"]')
                   .text('Save')
                   .attr('disabled', false);
               App.alert(e.responseText, 'Oops!');
           }
       });
   });
   $('body').off('click', '[role="access-set-all"]');
   $('body').on('click', '[role="access-set-all"]', function(e) {
       var traget = $(this).attr('target');
       $('.'+traget).attr('checked', 'checked');
   });
   $('body').off('click', '[role="access-reset-all"]');
   $('body').on('click', '[role="access-reset-all"]', function(e) {
       var traget = $(this).attr('target');
       $('.'+traget).attr('checked', false);
   });
</script>
@stop