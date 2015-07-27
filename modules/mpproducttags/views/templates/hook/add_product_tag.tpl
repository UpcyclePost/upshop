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
  color: #fff;
  background-image: url(data:image/svg+xml;
 base64, PD94bWwgdmVyc2lvbj0iMS4wIiA/PjxzdmcgeG1sbn…0iMSIgaGVpZ2h0PSIxIiBmaWxsPSJ1cmwoI2xlc3NoYXQtZ2VuZXJhdGVkKSIgLz48L3N2Zz4=);
  background-image: -webkit-linear-gradient(bottom, #6da134 0, #85c125 100%);
  background-image: -moz-linear-gradient(bottom, #6da134 0, #85c125 100%);
  background-image: -o-linear-gradient(bottom, #6da134 0, #85c125 100%);
  background-image: linear-gradient(to top, #6da134 0, #85c125 100%);
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
  display: inline-block;
  border-radius: 2px;
  font-size: 14px;
  margin: 0 5px 5px 0;
  padding: 4px 6px 4px 8px;
  text-decoration: none;
  transition: border .2s linear 0, box-shadow .2s linear 0;
  -moz-transition: border .2s linear 0, box-shadow .2s linear 0;
  -webkit-transition: border .2s linear 0, box-shadow .2s linear 0;
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
        preventSubmitOnEnter: true
    });
</script>