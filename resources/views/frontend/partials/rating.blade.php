<?php 
$rs = DB::table('rating')->where(['object_id' => $object_id, 'object_type' => $object_type])->get();
$totalRating = 0;
$total = 0;
foreach($rs as $rating){
	$totalRating += $rating->amount;
	$total += $rating->amount * $rating->score;
}
$star = ceil($total/$totalRating);
?>
<div class="rating-title" itemprop="name">Đánh giá bài viết:</div>
<div class="rating-summary">
    <input id="kartik" class="rating" data-stars="5" data-step="1" data-size="xs" title="" value="{{ $star }}" />
</div>
<div class="rating-action dot"  itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
		<span >Xếp hạng <span itemprop="ratingValue">{{ $star }}</span> - {{ $totalRating }} phiếu bầu</span>
</div>