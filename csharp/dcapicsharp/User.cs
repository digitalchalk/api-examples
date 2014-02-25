using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace dcapicsharp
{
    public class User
    {
        public String id { get; set; }
        public String firstName { get; set; }
        public String lastName { get; set; }
        public String username { get; set; }
        public String email { get; set; }
        public List<String> tags { get; set; }
        public String locale { get; set; }
        public String createdDate { get; set; }
        public String lastLoginDate { get; set; }
        public String password { get; set; }

    }
}
