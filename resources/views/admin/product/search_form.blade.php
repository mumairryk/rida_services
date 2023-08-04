<form action="" method="get">
    <div class="row">
        <div class="col-md-3 form-group">
            <label>Filter Products</label>
            <?php
            $filters = [
                        '1'=>'Product Title A-Z',
                        '2'=>'Product Title Z-A',
                        '3'=>'Created (Oldest First)',
                        '4'=> 'Created (Newest First)',
                        '5'=>'Updated (Oldest First)',
                        '6'=> 'Updated (Newest First)'
                         ]; 
             ?>
            <select class="form-control jqv-input product_cat" data-jqv-required="true"
            name="sort_type" data-role="select2" data-placeholder="Select Filter"
            data-allow-clear="true">
            <option value="">Filter Products</option>
            @foreach($filters as $key=>$val) 
                @if(isset($_GET['sort_type']) && $_GET['sort_type'] == $key)
                <?php  $selected = "selected"; ?>
                 @else 
                    <?php $selected  = "";  ?>
                @endif
                <option value="{{$key}}" {{$selected }}>{{$val}}</option>
            @endforeach
            
        </select>
        </div>
        <div class="col-md-3 form-group">
            <label>Category</label>
            <select class="form-control jqv-input product_cat select2" data-jqv-required="true"
            name="category" data-role="select2" data-placeholder="Select Categories"
            data-allow-clear="true">
                <option value="">All</option>
                @if(isset($category_list) && count($category_list) > 0)

                <?php 
                $id = ''; ?>
                @foreach($category_list as $parent_cat_id => $parent_cat_name)

                <option value="<?php echo $parent_cat_id; ?>"
                    <?php echo in_array($parent_cat_id, $category_ids) ? 'selected' : ''; ?>>
                   {{$parent_cat_name}}
                </option>
               
                

                @endforeach
                @endif
            </select>
        </div>
       {{-- <div class="col-md-2 form-group">
             <label>Price From</label>
             <input type="text" name="price_from" class="form-control">
        </div>
        <div class="col-md-2 form-group">
             <label>Price To</label>
             <input type="text" name="price_to" class="form-control">
        </div>--}}
        <div class="col-md-2 form-group">
             <label>Name</label>
              <input type="search" name="search_key" class="form-control form-control-sm"
                                        placeholder="" aria-controls="column-filter" value="{{ $search_key }}">
        </div>
        <div class="col-md-4 mt-4">
            
            @if($from || $to || $category || isset($_GET['search_key']) || $store_id)
            <a type="button" href="{{url('admin/products')}}" class="btn btn-primary float-right ml-2">Reset</a>
            @else
            <button type="reset" class="btn btn-primary float-right ml-2">Reset</button>
            @endif
            <button type="submit" class="btn btn-primary float-right">Filter</button>
        </div>
    </div>
</form>