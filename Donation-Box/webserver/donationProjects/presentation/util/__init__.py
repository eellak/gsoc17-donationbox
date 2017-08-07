"""
In this source file, i place my own utility functions.
"""

from django.http import Http404

from ..models import Donation_Project




def get_first_donation_project():
    """
    Function where returns all entries for the first donation project ( of course with id = 1 )

    :return: All entries for the first donation project or raise an Http404 exception.
    """

    try:
        donation_project = Donation_Project.objects.get( id = 1 )
    except Donation_Project.DoesNotExist:
        raise Http404("Does not exist donation projects yet in the local database! :-) ")

    return donation_project




def next_project_id( current_id ):
    """
    Function that returns the next donation project ID number.

    Attention! The order of projects ids may be unexpected, may not be normal, because there's possible
    to delete some donation projects. So the order of the donation project ids it ends up having this form :
    1, 2, 9, 10, 11, 23,.. or
    18, 23, 24, 25,... from the normal order :
    1, 2, 3, 4, 5,...

    :param current_id: The id of the current donation project.
    :return: - The id of the next one donation project, or
             - The id of the first donation project.
    """

    found_the_next_one = False

    for current_project in Donation_Project.objects.all():
        if found_the_next_one:
            return current_project.id
        if current_project.id == current_id:
            found_the_next_one = True

    return Donation_Project.objects.all().first().id




def previous_project_id( current_id ):
    """
    Function that returns the previous donation project ID number.

    Attention! The order of projects ids may be unexpected, may not be normal, because there's possible
    to delete some donation projects. So the order of the donation project ids it ends up having this form :
    1, 2, 9, 10, 11, 23,.. or
    18, 23, 24, 25,... from the normal order :
    1, 2, 3, 4, 5,...

    :param current_id: The id of the current donation project.
    :return: - The id of the previous one donation project, or
             - The id of the last donation project.
    """

    previous_id = Donation_Project.objects.all().last().id

    for current_project in Donation_Project.objects.all():
        if current_project.id == current_id:
            return previous_id
        previous_id = current_project.id


