# MyPHP Example Application
This source code serves to demonstrate:
1. Load balancing of pods when they are scaled.
2. Customization of services using environment variables via secrets/configmaps.
3. Simple Source-to-Image(S2I) customization of the build image.

Variables:
  DB_HOST - database server ip/name
  DB_USER - database user
  DB_PASS - database users password
  DB_NAME - database name
  MSG     - simple variable
  VALUE1  - simple variable
  
Todo:
- Implement persistent data storage through volume mapping.
