from django.db import models

# Create your models here.

class Donation_Project( models.Model ):
    title                   = models.CharField( max_length=200 )
    text                    = models.TextField()
    current_amount          = models.PositiveIntegerField()
    target_amount           = models.PositiveIntegerField()
    video_URL               = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/videos/', match = '*.mp4$' )
    image_URL               = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/images/' )
    stylesheet_URL          = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/css/', match = '*.css$' )
    start_date              = models.DateField()
    end_date                = models.DateField()
    last_updated_datetime   = models.DateTimeField( auto_now = True )



    def __str__(self):
        return self.title
