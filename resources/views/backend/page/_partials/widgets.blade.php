<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">{!! trans('backend.page.widgets') !!}</h3>

  </div><!-- /.box-header -->
  <div class="box-body">
    <div class="row">
      <div class="col-sm-12">
        <div class="form-group">
          <div class="input-group input-group">
            <form action="{{ route('zxadmin.widgetnode.store', [$page->id, $templateblock->identifier]) }}" id="widgetNodeForm" method="post">
            {!! csrf_field() !!}
             <select class="select2 form-control" id="widgetsList">
              <option disabled selected>{!! trans('backend.page.choose_widget') !!}</option>
              @foreach(Widgets::frontend()->groupByAuthors()->all($page->type) as $author => $widgets)
                <optgroup label="{{ $author }}">
                @foreach($widgets as $widget)
                  <option
                    data-namespace="{{ $widget->getFullName() }}"
                    data-title="{{ $widget->title }}"
                    data-config='[]'
                  >{{ $widget->title }} : {{ $widget->description }}</option>
                @endforeach
                </optgroup>
              @endforeach
             </select>
             <input type="hidden" id="widgetNamespace" name="namespace">
             <input type="hidden" id="widgetTitle" name="title">
             <input type="hidden" id="widgetConfig" name="config">
            </form>
            <span class="input-group-btn">
              <button id="addWidget" class="btn btn-primary" type="button" style=""> {!! trans('backend.page.add_widget') !!}</button>
            </span>
          </div><!-- /input-group -->
        </div>
      </div>
    </div>

    <div id="widgetNodes" data-nodes = "{{ $page->nodes()->whereTemplateblockId($templateblock->id)->sorted()->get() }}" data-route="{{ route('zxadmin.widgetnode.update', [$page->id, $templateblock->identifier, '']) }}"></div>

    <script type="x-tmpl-mustache" id="widgetNodesTemplate">
      <div class="row">
        <div class="col-sm-12">
          <label>{!! trans('backend.page.list_widgets_block') !!}</label>
          <ul class="widgets-list" data-entityname="Widgetnode" data-urlSort="{{ route('zxadmin.sort') }}">
            @{{#.}}
            @{{#is_enabled}}
            <li data-id="@{{id}}">
            @{{/is_enabled}}

            @{{^is_enabled}}
            <li class="disabled" data-id="@{{id}}">
            @{{/is_enabled}}

              <span class="handle">
                <i class="fa fa-ellipsis-v"></i>
                <i class="fa fa-ellipsis-v"></i>
              </span>

              @{{#is_enabled}}
              <input type="checkbox" checked>
              @{{/is_enabled}}

              @{{^is_enabled}}
              <input type="checkbox">
              @{{/is_enabled}}

              <span class="text page-widget-name" data-name="title" data-type="text" data-widget-id = "@{{id}}" data-url="@{{_route}}/@{{id}}">@{{title}}</span>
              <div class="tools">
                <a href="@{{_route}}/@{{id}}/edit" class="btn btn-xs btn-primary"><i class="fa fa-wrench" data-type="setting"></i> {!! trans('backend.page.widget_setting') !!}</a>

                <form method="POST" action="@{{_route}}/@{{id}}" accept-charset="UTF-8" style="display:inline">
                <input name="_method" type="hidden" value="DELETE">
                {{ csrf_field() }}
                    <button class="btn btn-xs btn-danger" type="button" data-toggle="modal" data-target="#confirmWidgetAction" data-title="@{{title}}" data-message="{!! trans('backend.page.delete_widget_confirmation') !!}">
                        <i class="fa fa-trash-o"></i> {!! trans('backend.page.delete_widget') !!}
                    </button>
                </form>
              </div>
            </li>
            @{{/.}}
          </ul>
        </div>
      </div>
    </script>
  </div>
</div>
@include('backend::widget.modals.delete')
