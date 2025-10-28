import { NavLink } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { ArrowUpRightIcon } from "lucide-react";

export function SiteHeader() {
  return (
        <nav className="flex gap-8 p-4">  
          <h1 className="text-xl font-semibold flex items-center gap-2">Dwarf <ArrowUpRightIcon /></h1>
          <div className="flex gap-2"> 
          <NavLink to="/">
            <Button variant="outline">Home</Button>
          </NavLink>
          <NavLink to="/create">
            <Button variant="outline">Create</Button>
          </NavLink>
          </div>
        </nav>
  );
}
