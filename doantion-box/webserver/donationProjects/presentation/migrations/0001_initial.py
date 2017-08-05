# -*- coding: utf-8 -*-
# Generated by Django 1.11.4 on 2017-08-04 19:07
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    initial = True

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='Donation_Project',
            fields=[
                ('id', models.AutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('title', models.CharField(max_length=200)),
                ('text', models.TextField()),
                ('current_amount', models.PositiveIntegerField()),
                ('target_amount', models.PositiveIntegerField()),
                ('video_URL', models.FilePathField(match='*.mp4$', max_length=200, null=True, path='/home/donatonProject_files/videos/', recursive=True)),
                ('image_URL', models.FilePathField(max_length=200, null=True, path='/home/donatonProject_files/images/', recursive=True)),
                ('stylesheet_URL', models.FilePathField(match='*.css$', max_length=200, null=True, path='/home/donatonProject_files/css/', recursive=True)),
                ('start_date', models.DateField()),
                ('end_date', models.DateField()),
                ('last_updated_datetime', models.DateTimeField(auto_now=True)),
            ],
        ),
    ]
