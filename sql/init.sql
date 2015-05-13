insert into hook_type (name) values ('BOOLEAN');
insert into hook_type (name) values ('TEXT');
insert into hook_type (name) values ('IMAGE');

insert into template_type (name) values ('HEADER');
insert into template_type (name) values ('FOOTER');
insert into template_type (name) values ('SECTION');

insert into role (name) values ('CONFIG_ADMIN');
insert into role (name) values ('TAG_ADMIN');
insert into role (name) values ('CATEGORY_ADMIN');
insert into role (name) values ('LOG_ADMIN');
insert into role (name) values ('REDIRECT_ADMIN');
insert into role (name) values ('TEMPLATE_ADMIN');
insert into role (name) values ('PAGE_ADMIN');
insert into role (name) values ('GROUP_ADMIN');

insert into log_type (code, reference) values ('LOGIN', 'account');
insert into log_type (code, reference) values ('LOGOUT', 'account');
insert into log_type (code, reference) values ('LOGIN_ATTEMPT', 'account');

