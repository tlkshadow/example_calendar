<?php
include __DIR__ . '/../bootstrap.php';
/* @var \DateTime $currentMonth */
/* @var App\Calendar\Renderer\CalendarRenderer $calendar */
/* @var App\Calendar\Paginator\CalendarPaginator $paginator */
?><html>
    <head>
        <link rel="stylesheet" href="css/layout.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-2 text-left">
                    <a href="?date=<?php echo $paginator->getPreviousYear()->format('Y-m'); ?>" class="btn btn-success"><i class="glyphicon glyphicon glyphicon-step-backward"></i></a>
                    <a href="?date=<?php echo $paginator->getPreviousMonth()->format('Y-m'); ?>" class="btn btn-success"><i class="glyphicon glyphicon-chevron-left"></i></a>
                </div>
                <div class="col-md-8 text-center">
                    <h4><?php echo $currentMonth->format('F Y'); ?></h4>
                </div>
                <div class="col-md-2 text-right">
                    <a href="?date=<?php echo $paginator->getNextMonth()->format('Y-m'); ?>" class="btn btn-success"><i class="glyphicon glyphicon-chevron-right"></i></a>
                    <a href="?date=<?php echo $paginator->getNextYear()->format('Y-m'); ?>" class="btn btn-success"><i class="glyphicon glyphicon glyphicon-step-forward"></i></a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="calendar <?php echo strtolower($currentMonth->format('F')); ?> year-<?php echo $currentMonth->format('Y'); ?>">
                    <?php echo $calendar->render(); ?>
                </div>
            </div>
        </div>
    </body>
</html>