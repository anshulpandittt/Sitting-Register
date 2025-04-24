INSERT INTO public.links_t (linkid,linklang,baselinkid,level2,level3,linkeng,level1) VALUES (1500,'Periphery',1500,0,0,'Periphery',80);
INSERT INTO public.links_t (linkid,linklang,baselinkid,level2,level3,linkeng,level1) VALUES (1501,'Sitting Register',1501,1,0,'Sitting Register',80);
INSERT INTO public.links_t (linkid,linklang,baselinkid,level2,level3,linkeng,level1,formname) VALUES (1502,'Daily Sitting Entry',1502,1,1,'Daily Sitting Entry',80,'periphery/Sitting%20Register/daily_sitting.php');
INSERT INTO public.links_t (linkid,linklang,baselinkid,level2,level3,linkeng,level1,formname) VALUES (1503,'Daily Sitting Record',1503,1,3,'Daily Sitting Record',80,'periphery/Sitting%20Register/view_all_data.php');
INSERT INTO public.links_t (linkid,linklang,baselinkid,level2,level3,linkeng,level1,formname) VALUES (1504,'Daily Sitting Monitoring',1504,1,4,'Daily Sitting Monitoring',80,'periphery/Sitting%20Register/subordinate_data.php');

INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,2,1500);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,6,1500);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,10,1500);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,13,1500);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,30,1500);

DELETE FROM public.utype_link WHERE utype='6' AND linkid='1501';
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,10,1501);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,30,1501);

DELETE FROM public.utype_link WHERE utype='6' AND linkid='1502';
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,10,1502);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,30,1502);

DELETE FROM public.utype_link WHERE utype='6' AND linkid='1503';
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,10,1503);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,30,1503);

INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,10,1504);
INSERT INTO public.utype_link (amd,utype,linkid) VALUES (1,30,1504);
