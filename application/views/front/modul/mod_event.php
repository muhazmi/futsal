<div class="bs-callout bs-callout-primary mt-3">
  <h4><i class="fa fa-newspaper-o"></i> Event Terbaru</h4>
</div>
<ul class="list-group">
  <?php
  foreach ($event_sidebar as $event_sidebar) {
  ?>
    <li class="list-group-item">
      <span class="badge">NEW</span>
      <?php echo anchor('event/read/' . $event_sidebar->slug_event . '', '' . $event_sidebar->nama_event . '') ?>
    </li>
  <?php } ?>
</ul>