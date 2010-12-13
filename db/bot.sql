PRAGMA foreign_keys=OFF;
BEGIN TRANSACTION;
CREATE TABLE "tokens" 
(
	"token_name" VARCHAR PRIMARY KEY NOT NULL ,
	"token_value" VARCHAR
);
INSERT INTO "tokens" VALUES('request_token','');
INSERT INTO "tokens" VALUES('request_token_secret','');
INSERT INTO "tokens" VALUES('access_token','');
INSERT INTO "tokens" VALUES('access_token_secret','');
INSERT INTO "tokens" VALUES('dm_since_id','');
INSERT INTO "tokens" VALUES('mentions_since_id','');
INSERT INTO "tokens" VALUES('last_follower_id','');
INSERT INTO "tokens" VALUES('last_heartbeat','');
CREATE TABLE "mentions"
(
	"timestamp" DATETIME,
	"data" TEXT
);
CREATE TABLE "dms" (
	"timestamp" DATETIME, 
	"data" TEXT);
COMMIT;
