# URLshortner

Created for MayaData hackathon on 19/04/2021 by Divya Shiv Pandey(1805120-KIIT)

SETUP:
1) database.sql:
It is to be imported in the servers database(in my case MYSQL was used). It will create a table to store the shortened urls, authKeys and expiry etc.
2) config.php:
It contains all the configuration data like database name password domain name etc.make sure to configure it right.
3) short.php:
It is resposible to shorten urls and provide with shortened url and authkey. AuthKey is only generated once so make sure to keep it when generating url as it is required to modify generated url.
4) index.php:
This file redirects all the incoming shortened url request to their original urls only if it has not expired. Make sure to check expiry in new tab due to storing of site in cache.
5) change.php:
It is used to change the destination url of a shortened url by providing auth, destination url and shortened code.

USAGE:
1)Generation of short url:
	http://localhost:PORT/short.php?url=INSERT_URL_HERE&time=INESRT_TIME_HERE_IN_MINUTES
	or
	http://127.0.0.1:8080/chaos/short.php?url=INSERT_URL_HERE&time=INESRT_TIME_HERE_IN_MINUTES
	or
	YourDomain/short.php?url=INSERT_URL_HERE&time=INESRT_TIME_HERE_IN_MINUTES
 Expected Output:URL: 127.0.0.1:8080/chaos?code=2wT AUTH KEY: 6lAeo1dMwBqhk3Mr9Rpu CODE: 2wT

2) Change of Short URL:
	http://127.0.0.1:8080/chaos/change.php?auth=INSERT_AUTH_KEY&url=INSERT_NEW_URL&code=INSERT_CODE_OF_OLD_URL
	or
	http://localhost:PORT/chaos/change.php?auth=INSERT_AUTH_KEY&url=INSERT_NEW_URL&code=INSERT_CODE_OF_OLD_URL
 Expected Output: Successfuly Change or unauthorised Access

3) Shortened URL:
 This will be provided in the step1.
	YourDomain/chaos?code=2wT
	or
	127.0.0.1:8080/chaos?code=2wT
Expected Output: Redirect to url IF LINK NOT EXPIRED.

NOTE: While testing for link expiry, Please open the shortened url in new tab as browser sometimes loads webpage from its cache memory.
