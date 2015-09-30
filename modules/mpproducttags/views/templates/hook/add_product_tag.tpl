<style>
.tag_validate{
  clear: both;
  color: #7F7F7F;
  font-family: inherit;
  font-size: 12px;
  font-style: italic;
  text-align: left;
}
.tm-tag {
  background-color: #ddd;
  color: #000;
  display: inline-block;
  border-radius: 2px;
  font-size: 14px;
  margin: 0 5px 5px 0;
  padding: 4px 6px 4px 8px;
  text-decoration: none;
  vertical-align: middle;
  line-height: normal;
}
</style>
<div class="form-group">
		<label class="add_one" for="tags">{l s='Product Tag :' mod='marketplace'}</label>
	<span>
    <input type="text" id="tags" name="tags" value="" placeholder="Type a tag and press enter or tab key..." class="account_input form-control tm-input" />
  </span>
  <p class="tag_validate">Type a tag then hit the tab or enter key. (Tag Examples : steampunk, boho, shabby chic, trashion, vintage, metal, wood)</p>
</div>
<div class="tags-container">

</div>
<p class="tag_validate">Remove existing tags, by clicking on the x next to the tag name</p>
<script>
  $('.tm-input').tagsManager({
        tagsContainer: '.tags-container',
        tagCloseIcon: '<i class="fa fa-fw"></i>',
        prefilled: ["{$product_tag}"],
        preventSubmitOnEnter: true,
    });
</script>