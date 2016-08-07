<div class="box-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label("content[title]", trans("backend.ad.ad_title"), ['class' => 'label-text']) !!}
				{!! Form::text("content[title]", null, ['class' => 'form-control', 'placeholder' => 'Ex : Lamborghini egoista V10 600 hp']) !!}
			</div>
			<div class="form-group">
				{!! Form::label("content[body]", "&nbsp;", ['class' => 'label-text']) !!}
				{!! Form::textarea("content[body]", null, ['class' => 'form-control wysihtml5']) !!}
			</div>
			<div class="form-group">
		    {!! Form::label("photos", trans("backend.ad.photos"), ['class' => 'label-text']) !!}
		  </div>
		  <div id="photos" class="row" data-can-add-photo="{{ $adtype->can_add_pic && $adtype->nbr_pic > 0 && (!isset($ad) || isset($ad) && $adtype->can_update_pic) }}" data-max-photos="{{ $adtype->nbr_pic }}" data-photos="{{ isset($ad) ? $ad->photos : '[]' }}">

		    <script type="x-tmpl-mustache" id="newPhotoTemplate">
		      <div data-empty-photo class="col-md-3 uploadedPhoto">
		        <div class="thumbnail">
		          <span class="image"><center><i class="fa fa-picture-o" style="font-size:60px"></i></center></span>
		          <div class="caption">
		            <div class="btn-group btn-group-justified" role="group">
		              <div class="btn-group">
		                <div class="btn btn-xs btn-primary btn-file btn-block"><i class="fa fa-picture-o"></i> <span class="text">{!! trans('backend.ad.add_a_photo') !!}</span> <input class="addAdPhotos" type="file" name="photos[]"></div>
		              </div>
		            </div>
		          </div>
		        </div>
		      </div>
		    </script>
		    <script type="x-tmpl-mustache" id="photoTemplate">
		      @{{#.}}
		        <div class="col-md-3" data-photo>
		          <div class="thumbnail">
		            <img class="img-rounded" src="{{ image_route('medium', '') }}/@{{path}}" />
		            @if (!isset($ad) || isset($ad) && $adtype->can_update_pic)
		            <div class="caption">
		              <button type="button" class="btn btn-xs btn-block btn-danger remove-photo"><i class="fa fa-remove"></i> {!! trans('backend.ad.delete') !!}</button>
		            </div>
		            @endif
		          </div>
		          <input type="hidden" name="oldPhotos[][path]" value="@{{path}}">
		        </div>
		      @{{/.}}
		    </script>
		  </div>
			<div class="form-group">
		    {!! Form::label("inputVideo", trans("backend.ad.videos"), ['class' => 'label-text']) !!}
		    @if ($adtype->can_add_video && $adtype->nbr_video > 0 && (!isset($ad) || isset($ad) && $adtype->can_update_video))
		    <div id="form-add-video" class="input-group input-group-sm">
		      <input type="text" id="inputVideo" class="form-control" placeholder="https://www.youtube.com/watch?v=ujn7jEQ4ib4" />
		      <span class="input-group-btn">
		        <button id="add_video" class="btn btn-success" type="button">{!! trans('backend.ad.add_video') !!}</button>
		      </span>
		    </div><!-- /input-group -->
		    @else
		    <div class="alert alert-info">
		      <i class="fa fa-info-circle"></i> {!! trans('backend.ad.not_enough_permission_to_add_video') !!}
		    </div>
		    @endif
		  </div>
		  <div id="videos" class="row" data-max-videos="{{ $adtype->nbr_video }}" data-videos="{{ isset($ad) ? $ad->videos : '[]' }}">
		    <script type="x-tmpl-mustache" id="videoTemplate">
		      @{{#.}}
		        <div class="col-md-4" data-video id="video_@{{link}}">
		            <div class="thumbnail">
		              <div class="js-lazyYT" data-youtube-id="@{{link}}" data-ratio="16:9"></div>
		              @if (!isset($ad) || isset($ad) && $adtype->can_update_video)
		              <div class="caption">
		                <a href="javascript:void(0)" class="btn btn-danger btn-xs btn-block remove-video" data-video-link="@{{link}}"><i class="fa fa-remove"></i> {!! trans('backend.ad.delete') !!}</a>
		              </div>
		              @endif
		            </div>
		          <input type="hidden" name="videos[][link]" value="@{{link}}">
		        </div>
		      @{{/.}}
		    </script>
		  </div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{!! Form::label("ZxAjaxMember", trans("backend.ad.user"), ['class' => 'label-text']) !!}
				<select class="select2 form-control" id="zedx-ad-user" name="user_id" data-url = "{{ route('zxadmin.user.index') }}" data-placeholder= "{!! trans('backend.ad.choose_a_user') !!}">
					@if (isset($ad))
					<option value="{{ $ad->user->id }}">{{ $ad->user->name }}</option>
					@endif
				</select>
			</div>

			<div class="form-group">
				{!! Form::label("geolocation_data", trans("backend.ad.geolocation"), ['class' => 'label-text']) !!}
				<div class="input-group input-group">
        	<select id="zedx-ad-geolocation" name="geolocation_data" class="select2 form-control" data-placeholder= "{!! trans('backend.ad.choose_a_geolocation') !!}">
					@if (isset($ad))
					<option value="{{ $ad->geolocation->json }}">{{ $ad->geolocation->formatted_address }}</option>
					@endif
					</select>
          <span class="input-group-btn">
            <button id="findme" class="btn btn-primary" type="button" style=""><i class='fa fa-search'></i> {!! trans('backend.ad.geolocate_me') !!}</button>
          </span>
	      </div><!-- /input-group -->
			</div>

			<div class="form-group">
				{!! Form::label("category_id", trans("backend.ad.category"), ['class' => 'label-text']) !!}
				<select class="select2 form-control" id="category_id" name="category_id">
					<option>{!! trans("backend.ad.choose_a_category") !!}</option>
				@foreach (ZEDx\Models\Category::all() as $category)
				@if ($category->isLeaf())
					<option value="{{ $category->id }}" {{ isset($ad) && $category->id == $ad->category_id ? 'selected': '' }} data-category-api-url= "{{ route('zxajax.category.adFields', $category->id) }}">{{ $category->name }}</option>
				@else
					<optgroup label="{{ $category->name }}">
				@endif
				@endforeach
				</select>
			</div>
  		@include('backend::ad._partials.fields')
		</div>
	</div>
		@include ('backend::errors.list')
</div>
<div class="box-footer">
	{!! Form::submit($submitButton, ["class" => "btn btn-primary pull-right"]) !!}
</div>
