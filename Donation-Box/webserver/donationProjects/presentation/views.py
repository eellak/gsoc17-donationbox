from django.shortcuts import render

from .models import Donation_Project
from .util import get_first_donation_project
from .util import next_project_id
from .util import previous_project_id

# Create your views here.


def index(request):
    """
    Presents the data of the first donation project.

    :param request:
    :return: Presents the data of the first donation project or raise an Http404 exception if there doesn't exist
    donation projects.
    """

    # Queries
    current_project = get_first_donation_project()

    # Pass results to a dictionary
    context = { 'current_project': current_project,
                'next_project_id': next_project_id( current_project.id ),
                'previous_project_id': previous_project_id( current_project.id ) }

    return render( request, 'presentation/index.html', context )




def project(request, project_id ):
    """
    Returns for the given project_id ( donation project ) all his records in the database.

    This view is enabled when it is executed the URL pattern "http://127.0.0.1:8000/current_project/x", which means
    that requested the donation project with id "x".

    Attention! : If a request is made for an project_id that does not exist then returned information for the first
    donation project ( project_id = 1 ).
    Though again we are in the unlikely event, where the local sqlite3 database it has no donation project so far,
    then it returns an Http404 error, with a decent message. :)

    :param request:
    :param project_id: The id for the requested donation project.
    :return:
    """

    # Queries
    try:
        current_project = Donation_Project.objects.get( id = project_id )
    except Donation_Project.DoesNotExist:
        current_project = get_first_donation_project()

    # Pass results to a dictionary
    context = { 'current_project': current_project,
                'next_project_id': next_project_id( current_project.id ),
                'previous_project_id': previous_project_id( current_project.id ) }

    return render( request, 'presentation/index.html', context )


