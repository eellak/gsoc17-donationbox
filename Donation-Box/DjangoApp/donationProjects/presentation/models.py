from pydoc import Doc

from django.db import models

# Create your models here.

class Donation_Project( models.Model ):
    """
    The Donation_Project model.

    This is the base table. It contain all the information we think needs to be in the donation box for each donation
    project. This data comes directly from the remote database and updated or inserting new records only from the
    remote database (when it is possible to communicate and synchronize with it).

    Important! : This table is *not* updated *by local* data!! Its data is a small snapshot from the remote database!

    This table is mainly used for the presentation of the donation projects from the Django application.
    In the unique case that combines the data with the temporary table ("temporary_Donation_Project"), is for the total
    amount that collected for a donation project. The "current_amount" field in this table is added together with the
    sum of the "amount" field of the entries for this project, in the "temporary_Donation_Project" table (if they exist).
    Because there is a case for a donation project have been donations in this box and may not  has not been able to
    synchronize with the remote database and therefore, that this table is not fully synchronized as well.

    The beloved Django creates * automatically * and one more field named "id".

    Attention! The fields :
        - "video_URL",
        - "stylesheet_URL",
                            it may be NULL, but it must not be blank!
    That is, if it is null (empty), it means they have no value, and not how they may have a zero value ("").
    Read more about it here: https://docs.djangoproject.com/en/1.11/ref/models/fields/#null
    """

    title                   = models.CharField( max_length=200 )
    text                    = models.TextField()
    current_amount          = models.PositiveIntegerField()
    target_amount           = models.PositiveIntegerField()
    image_URL               = models.FilePathField( max_length = 200, recursive = True, path = '/home/donatonProject_files/images/', match = '(*.png$)|(*.jpg$)' )
    video_URL               = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/videos/', match = '*.mp4$' )
    stylesheet_URL          = models.FilePathField( max_length = 200, recursive = True, null = True, path = '/home/donatonProject_files/css/',    match = '*.css$' )
    start_date              = models.DateField()
    end_date                = models.DateField()
    last_modified_datetime  = models.DateTimeField()


    def __str__(self):
        return self.title




class temporary_Donation_Project( models.Model ):
    """
    The temporary_Donation_Project model.

    This is a temporary table. Whenever a donation is made in locally to the donation box updated this table.
    For what donation project (id), what amount donated in and the exact time it was made.

    When updating/syncing is possible to the remote database the data of this table is sent to the remote database and
    deleted from it.

    This table is maintained until the local box can synchronize with the removed database.
    Τhe time it will successfully sync with the remote database, this table must be empty and the table
    "Donation_Project" be fully up to date!!

    Whenever a table entry is updated/inserting the "last_updated_datetime" field of the corresponding record is automatically
    updated. Assigned to this field, the current system timestamp.

    Important! : This is the local temporary table that is updated *every time* a donation is made to the donation box.

    Remember: The beloved Django creates * automatically * and one more field named "id".

    Note about ForeignKey : The field on the related object that the relation is to.
    By default, Django uses the primary key of the related object.
    More : https://docs.djangoproject.com/en/1.11/ref/models/fields/#django.db.models.ForeignKey.to_field
    """

    amount = models.PositiveIntegerField()
    last_updated_datetime = models.DateTimeField( auto_now = True )
    project_id = models.ForeignKey(Donation_Project, on_delete=models.CASCADE)


    def __str__(self):
        return self.id

