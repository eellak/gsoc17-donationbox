from django.db import models

# Create your models here.

class Donation_Project( models.Model ):
    """
    The Donation_Project model.

    Some observations on the application model and database.
    Whenever a database entry is updated the "last_updated_datetime" field of the corresponding record is automatically
    updated. Assigned to this field, the current system timestamp.
    The beloved Django creates * automatically * and one more field named "id".

    Attention! The fields : "video_URL", "image_URL" and "stylesheet_URL", it may be NULL, but it must not be blank!
    That is, if it is null (empty), it means they have no value, and not how they may have a zero value ("").
    Read more about it here: https://docs.djangoproject.com/en/1.11/ref/models/fields/#null
    """

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

