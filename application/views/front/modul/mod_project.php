<div class="widget widget-popular-posts"><h4>Project Terbaru <span class="head-line"></span></h4>
  <ul>
    <?php foreach($project_sidebar as $project){ ?>
    <li>
      <div class="widget-thumb">
        <a href="<?php echo base_url('project/').$project->slug_project ?>"><img src="<?php echo base_url('assets/images/project/').$project->foto.$project->foto_type ?>" alt="<?php echo $project->judul_project ?>"></a>
      </div>
      <div class="widget-content">
        <h5><a href="<?php echo base_url('project/').$project->slug_project ?>"><?php echo $project->judul_project ?></a></h5>
        <span><?php echo date("j F Y", strtotime($project->created_at)); ?></span>
      </div>
      <div class="clearfix"></div>
    </li>
    <?php } ?>
    <div align="right">
      <a href="<?php echo base_url('project') ?>" class="main-button"><font style="color:white">Selengkapnya <i class="fa fa-angle-right"></i></font></a>
    </div>
  </ul>
</div>
