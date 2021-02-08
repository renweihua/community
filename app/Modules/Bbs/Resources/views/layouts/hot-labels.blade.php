@if(!empty($labels))
<li class="widget widget_ui_tags aos-init" data-aos='fade-up'>
    <div class="widget-title"><span class="icon"><i class="fa fa-tags"></i></span>
        <h3>热门标签</h3>
    </div>
    <div class="items">
        @foreach($labels as $label)
        <a href="{{ url('label/' . $label->label_id) }}"> {{ $label->label_name }} </a>
        @endforeach
    </div>
</li>
@endif
