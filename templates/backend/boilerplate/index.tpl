{extends file="backend/ext_js/index.tpl"}

{block name="backend_index_css" append}
    <!-- Common CSS -->
    <link href="{link file='engine/backend/css/icons4.css'}"  rel="stylesheet" type="text/css" />
    <link href="{link file='engine/backend/css/modules.css'}" rel="stylesheet" type="text/css" />
{/block}

{block name="backend_index_javascript" append}
<script type="text/javascript">
    Ext.application({
        name: 'Boilerplate',
        appFolder: '{url action=load}',
        controllers: [
            'main'
        ],
        launch: function() {}
    });
</script>
{/block}
