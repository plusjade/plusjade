
<?php
  $status = array('draft'=>'','publish'=>'');
  $status[$item->status] = 'selected="selected"';
?>
<span class="on_close"><?php echo $js_rel_command?></span>

<form action="/get/edit_blog/edit?item_id=<?php echo $item->id?>" method="POST" class="ajaxForm">  
  <input type="hidden" name="blog_id" value="<?php echo $item->blog_id?>">

  
  <div id="common_tool_header" class="buttons">
    <button type="submit" name="add_images" class="jade_positive">Save Changes</button>
    <div id="common_title">Update Blog Post</div>
  </div>  

  <ul class="common_tabs_x ui-tabs-nav">  
    <li><a href="#blog_body"><b>Body</b></span></a><li>
    <li><a href="#blog_params"><b>Title & Attributes</b></span></a><li>
  </ul>
  
  <div class="common_full_panel">
  
    <div id="blog_body" class="toggle fieldsets">
      <textarea name="body" class="render_html"><?php echo $item->body?></textarea>
    </div>

    
    <div id="blog_params" class="toggle fieldsets">
      <div class="common_half_left">
        <b>Title</b>
        <br/><input type="text" name="title" value="<?php echo $item->title?>" class="send_input" rel="text_req" style="width:300px">
        <br/><br/>
        <b>Url</b>
        <br/><input type="text" name="url" value="<?php echo $item->url?>" class="auto_filename receive_input" rel="text_req" style="width:300px">
        <br/><br/>
        <b>Status</b>
        <br/><select name="status">
          <option <?php echo $status['draft']?>>draft</option>
          <option <?php echo $status['publish']?>>publish</option>
        </select>
        
        <br/><br/>
        <?php if($is_sticky):?>
          <input type="checkbox" name="sticky" value="unstick"> <b>Remove Sticky</b>
        <?php else:?>
          <input type="checkbox" name="sticky" value="stick"> <b>Make sticky</b>
        <?php endif;?>
        <br/>
        <div style="margin:5px 7px;">
          A <em>sticky</em> blog post will show on the main blog navigation under "sticky posts". 
        </div>
      </div>
      
      <div class="common_half_right">
        <b>Tags</b>
        <div style="margin:5px 7px;">
          Separate tags with a space. Multi-word tags should be combined.
          <br/>ex: business web-development marketing
        </div>
        
        <div class="blog_post_tag_pane">
          <b>Add Tags</b>
          <br/><input type="text" name="tags" style="width:200px">
          <ul id="tag_output" class="common_tag_list"></ul>
        </div>
        
        
        <div class="blog_post_tag_pane">
          <b>Current Tags</b>
          <ul class="common_tag_list">
            <?php
            if(!empty($item->tag_string))
            {
              $tags = explode(',', $item->tag_string);
              foreach($tags as $tag)
              {
                $pair = explode('_', $tag);
                echo '<li><span>' . $pair['0'] . '</span> <a href="/get/edit_blog/delete_tag/' .$pair['1']. '" class="delete_blog_tag">[x]</a></li>';
              }
            }
            ?>
          </ul>
        </div>
      
      </div>
    </div>
    

  </div>
  
</form>

<script type="text/javascript">

  $(".common_tabs_x li a").click(function(){
    $('.common_tabs_x li a').removeClass('active');
    var pane = $(this).attr('href');
    $('.common_full_panel div.toggle').hide();
    $('.common_full_panel div'+ pane).show();
    return false;
  });
  $('.common_tabs_x li a:first').click();
  

// filter and update new tags view.    
  $('input[name="tags"]').keyup(function(){
    var input = $(this).val().replace(/[^-a-z0-9_ ]/ig, '-');
    $(this).val(input);
    
    var list = '';
    $.each($.trim(input).split(' '), function(){
      list += '<li><span>'+ this +'</span></li>';
    });
    $('ul#tag_output').empty().append(list);  
    
  });
  
// delete a blog tag
  $('a.delete_blog_tag').click(function(){
    if(confirm('This cannot be undone. Remove this tag from the post?')){
      var $link = $(this);
      $.post($(this).attr('href'), function(){
        $link.parent().remove();
      });
    }
    return false;
  });



</script>


