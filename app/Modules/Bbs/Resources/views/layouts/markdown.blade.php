<!-- markdown 文本编辑器 -->
<link rel="stylesheet" type="text/css"
      href="{{ asset('/statics/editor.md-master/css/editormd.css?v=' . cnpscy_config('resource_version_number')) }}"/>

<script type="text/javascript" src="{{ asset('/statics/editor.md-master/lib/marked.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
<script type="text/javascript" src="{{ asset('/statics/editor.md-master/lib/prettify.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
<script type="text/javascript" src="{{ asset('/statics/editor.md-master/lib/raphael.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
<script type="text/javascript" src="{{ asset('/statics/editor.md-master/lib/underscore.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
<script type="text/javascript" src="{{ asset('/statics/editor.md-master/lib/sequence-diagram.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
<script type="text/javascript" src="{{ asset('/statics/editor.md-master/lib/flowchart.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
<script type="text/javascript" src="{{ asset('/statics/editor.md-master/lib/jquery.flowchart.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>
<script type="text/javascript" src="{{ asset('/statics/editor.md-master/editormd.min.js?v=' . cnpscy_config('resource_version_number')) }}"></script>

<script type="text/javascript">
    console.log('mdeditorContent');
    mdeditorContent();
    function mdeditorContent() {
        // 使用国外的CDN，加载速度有时会很慢，或者自定义URL
        // You can custom KaTeX load url.
        editormd.katexURL  = {
            css : "{{ asset('/statics/editor.md-master/lib/KaTeX/0.3.0/katex.min.css?v=' . cnpscy_config('resource_version_number')) }}",
            js  : "{{ asset('/statics/editor.md-master/lib/KaTeX/0.3.0/katex.min.js?v=' . cnpscy_config('resource_version_number')) }}",
        };
        editormd.markdownToHTML("editormd-content", {
            htmlDecode      : "style,script,iframe",
            emoji           : true,
            taskList        : true,
            tex             : true,  // 默认不解析
            flowChart       : true,  // 默认不解析
            sequenceDiagram : true  // 默认不解析
        });
    }
</script>
