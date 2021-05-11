0. Investigated project: checked docker setup; checked symfony setup and configurations; checked makefile commands; checked postman collection; checked application domain.
1. Updated .env file to add more MYSQL related variables and use it in DATABASE_URL.
2. Init project using `make init`. (run twice since mysql have some startup delay and first run leads to error)
3. Exec bash of php container and checked symfony by trying `bin/console ca:cl`. Result - OK.
4. Exec /login endpoint in postman and faced typical cache permission issue.
5. Update docker setup to add ACL for `var` directory to fix cache and log permission problems. https://symfony.com/doc/current/setup/file_permissions.html#using-acl-on-a-system-that-supports-setfacl-linux-bsd
6. Investigate tests. Run `make tests` and inspect errors.
7. Fix existing tests.
8. Added PATCH /item/{id} endpoint
9. Covered update endpoint with unit and functional tests. Updated PUT /item endpoint in Postman collection.
10. Updated Nginx container default.config in order to add 433 ssl server and redirect all 80 port income requests to it.
11. Updated Makefile init section: added openssl cert generation step before building docker.
12. Updated nginx docker-compose section to mount ssl certs volume and add 443 port forwarding.
13. Installed symfony profiler package in order to pre-check how fast is API.
14. 