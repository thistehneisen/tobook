def main():
    with open('db_keys.txt') as f1, \
        open('current_keys.txt') as f2, \
        open('results.txt', 'w') as out:
        d1 = {}
        for line in f1:
            l = line.split()
            d1[l[0]] = ' '.join(l[1:])
        l1 = d1.keys()
        l2 = []
        for line in f2.read().splitlines():
            l2.append(line.replace('\'', '').split(' => ')[0])

        # compare 2 lists and sort
        result = list(set(l1) - set(l2))
        result.sort()

        # print out
        current = None
        for item in result:
            if item.find('_ARRAY_') > -1:
                pair = item.split('_ARRAY_')
                if pair[0] != current:
                    if current:
                        out.write("),\n")
                    current = pair[0]
                    out.write(
                        "'%s' => array(\n    '%s' => '%s',\n"
                        % (pair[0], pair[1], d1[item].replace("'", "\\'"))
                    )
                else:
                    out.write(
                        "    '%s' => '%s',\n" 
                        % (pair[1], d1[item].replace("'", "\\'"))
                    )
            else:
                if current:
                    out.write("),\n")
                current = None
                out.write("'%s' => '',\n" % item)

main()