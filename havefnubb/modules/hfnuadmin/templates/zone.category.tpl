{foreach $categories as $category}
    <div class="forumcat">
        <fieldset>
        <legend>{$category->cat_name|eschtml}</legend>    
        {zone 'hfnuadmin~forum',array('id_cat'=>$category->id_cat)}
        </fieldset>
    </div>
{/foreach}
