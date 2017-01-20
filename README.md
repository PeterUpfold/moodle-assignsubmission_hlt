Moodle Assignment Submission Module: HLT
========================================

A submission plugin for the Moodle assignment module that allows us to set a "set date" and "due date" calendar event in the student's calendar. 

Currently unstable and untested.


## Reference

    To test the patch: 

    # Hack a submission plugin to add a calendar event for the assignment (with a different eventtype than 'due') in the save_settings() method 
    # Create an assignment with a due date. Confirm that both this and the other events have been added to the calendar as expected. 
    # Edit the assignment to disable the due date. Confirm that the other event has not been removed. 
    # Hide the assignment. Confirm that the other event is hidden in the calendar 
    # Delete the assignment. Confirm that the other event is deleted. 
    Assignee 		Michael Aherne [ maherne ]```

https://tracker.moodle.org/browse/MDL-52013


