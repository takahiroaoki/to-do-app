<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        {block name="headTag"}{/block}
    </head>
    <body>
        <!-- header -->
        {include file="header.tpl"}

        <!-- content -->
        {block name="content"}{/block}
        
        <!-- footer -->
        {include file="footer.tpl"}
        
        <!-- import JavaScript file -->
        {block name="scriptTag"}{/block}
    </body>
</html>