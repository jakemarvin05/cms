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

-- login: admin
-- password: 72U6DnYp2kTd
insert into account(`login`, `email`, `first_name`, `last_name`, `password`, `salt`) values ('admin', 'admin@prism-communications.com', 'Admin', 'Admin', 'b498a05d9dc3390088684c084c86ba66dcb510e5dadbe002b42e0c370d83b283769cf2e0a6bfaa5b0578acc6783eb6fa5a3aea42f81f99dec410fe1d07698863', '7b997f8a7adc97894497c32356e26bb6e8762be4d29cbf0d65ba4ca05f21c81f13ce792abee650a5289311789bc6420a5550be1e9ef27664438cf5b754f19d7b');

