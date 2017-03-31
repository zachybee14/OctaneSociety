
// To create an Event your Json needs to be structured like this
{
	"fb_id":"111111111",
	"name":"That really cool meet", 
	"description":"its totally wicked awesome slammed shinny paint horsepower fest", 
	"days":{
		"1": {
	  		"date":"2016-02-04", 
	    	"start_time":"14:14:30", 
	    	"end_time":"23:14:30",
		},
		"2": {
	  		"date":"2016-02-05", 
	    	"start_time":"14:14:30", 
	    	"end_time":"23:14:30",
		},
	 	"3": {
	  		"date":"2016-02-06", 
	    	"start_time":"14:14:30", 
	    	"end_time":"23:14:30",
		}
	}, 
	"location":{
		"street": "2b julie lane", 
		"city": "hudson", 
		"state":"nh",
		"zip": "03051"
	}, 
	"type":"meet"
}


// To create a Cruise
{
    "event_id":"111111111",
    "name":"That really cool meet", 
    "start_time":"11:22:33",
    "estimated_duration":"30",
    "location":{
        "street": "2b julie lane", 
        "city": "hudson", 
        "state":"nh",
        "zip": "03051"
    },
    "stops":{
        "1": {
            "street":"2b julie lane", 
            "city":"hudson", 
            "state":"nh",
            "zip": "03051",
            "duration": "30"
        },
        "2": {
            "street":"20a mulberry street", 
            "city":"nashua", 
            "state":"nh",
            "zip": "03060",
            "duration": "30"
        },
        "3": {
            "street":"14 robin drive", 
            "city":"hudson", 
            "state":"nh",
            "zip": "03051",
            "duration": "30"
        }
	}
}