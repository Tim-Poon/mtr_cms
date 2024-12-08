import re
from os.path import dirname


def overwrite_site_setting(new_site: str):
    config_file_path = f'{dirname(dirname(__file__))}/config/common.py'
    # print(config_file_path)
    filename = open(config_file_path, 'r')
    content = filename.read()
    content_group = re.match('(.*)(SENSOR_SITE)([ \t\r\f\v\'\\w=]+)(.*)', content, re.DOTALL).groups()
    filename.close()
    print(content_group[2])

    new_content_group = (content_group[0], f"SENSOR_SITE = '{new_site}'", content_group[3])
    # print(new_content_group)

    new_content = ''.join(new_content_group)
    name = open(config_file_path, 'w')
    name.write(new_content)
    name.close()


if __name__ == "__main__":
    overwrite_site_setting('ABCDE')
